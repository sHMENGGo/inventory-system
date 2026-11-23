<?php

namespace App\Controllers;

use App\Models\ItemModel;
use App\Models\RequestCommentModel;
use App\Models\RequestModel;
use CodeIgniter\HTTP\ResponseInterface;

class RequestController extends BaseController
{
    private const STATUSES = ['pending', 'approved', 'received', 'rejected'];

    private RequestModel $requestModel;
    private ItemModel $itemModel;
    private RequestCommentModel $commentModel;

    public function __construct()
    {
        $this->requestModel = new RequestModel();
        $this->itemModel    = new ItemModel();
        $this->commentModel = new RequestCommentModel();
    }

    public function index(): ResponseInterface|string
    {
        $status = (string) ($this->request->getGet('status') ?? '');

        if ($status !== '' && ! in_array($status, self::STATUSES, true)) {
            return redirect()->to('/requests')->with('error', 'Invalid status filter.');
        }

        $builder = $this->requestModel
            ->select('request.*, item.name AS item_name, user.name AS user_name')
            ->join('item', 'item.id = request.item_id')
            ->join('user', 'user.id = request.user_id');

        if (! $this->isAdmin()) {
            $builder->where('request.user_id', $this->currentUser()['id']);
        }

        if ($status !== '') {
            $builder->where('request.status', $status);
        }

        return view('requests/index', [
            'requests' => $builder->orderBy('request.created_at', 'DESC')->findAll(),
            'status'   => $status,
            'statuses' => self::STATUSES,
        ]);
    }

    public function create(): string
    {
        return view('requests/form', [
            'request' => null,
            'items'   => $this->itemModel->orderBy('name', 'ASC')->findAll(),
            'action'  => site_url('requests'),
        ]);
    }

    public function store(): ResponseInterface
    {
        $itemId = (string) $this->request->getPost('item_id');

        if (! $this->isUuid($itemId)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Please choose a valid item.');
        }

        $rules = [
            'item_id'  => 'required|is_not_unique[item.id]',
            'quantity' => 'required|integer|greater_than[0]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $inserted = $this->requestModel->insert([
            'id'       => $this->uuid(),
            'user_id'  => $this->currentUser()['id'],
            'item_id'  => $itemId,
            'quantity' => (int) $this->request->getPost('quantity'),
            'status'   => 'pending',
        ]);

        if ($inserted === false) {
            return redirect()->back()->withInput()->with('error', 'Could not create the request.');
        }

        return redirect()->to('/requests')->with('message', 'Request submitted.');
    }

    public function show($id = null): ResponseInterface|string
    {
        $request = $this->findDetailed($id);

        if ($request === null) {
            return redirect()->to('/requests')->with('error', 'Request not found.');
        }

        if (! $this->canView($request)) {
            return redirect()->to('/requests')->with('error', 'You cannot view that request.');
        }

        $comments = $this->commentModel
            ->select('request_comment.*, user.name AS user_name, user.is_admin AS user_is_admin')
            ->join('user', 'user.id = request_comment.user_id')
            ->where('request_comment.request_id', $request['id'])
            ->orderBy('request_comment.created_at', 'ASC')
            ->findAll();

        return view('requests/show', [
            'request'  => $request,
            'comments' => $comments,
        ]);
    }

    public function edit($id = null): ResponseInterface|string
    {
        $request = $this->findDetailed($id);

        if ($request === null) {
            return redirect()->to('/requests')->with('error', 'Request not found.');
        }

        if ($request['user_id'] !== $this->currentUser()['id']) {
            return redirect()->to('/requests/' . $request['id'])->with('error', 'Only the requester can edit quantity.');
        }

        if ($request['status'] !== 'pending') {
            return redirect()
                ->to('/requests/' . $request['id'])
                ->with('error', 'Only pending requests can be edited.');
        }

        return view('requests/form', [
            'request' => $request,
            'items'   => null,
            'action'  => site_url('requests/' . $request['id']),
        ]);
    }

    public function update($id = null): ResponseInterface
    {
        $request = $this->findDetailed($id);

        if ($request === null) {
            return redirect()->to('/requests')->with('error', 'Request not found.');
        }

        if ($request['user_id'] !== $this->currentUser()['id']) {
            return redirect()->to('/requests/' . $request['id'])->with('error', 'Only the requester can edit quantity.');
        }

        if ($request['status'] !== 'pending') {
            return redirect()
                ->to('/requests/' . $request['id'])
                ->with('error', 'Only pending requests can be edited.');
        }

        $rules = [
            'quantity' => 'required|integer|greater_than[0]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        if (! $this->requestModel->update($id, [
            'quantity' => (int) $this->request->getPost('quantity'),
        ])) {
            return redirect()->back()->withInput()->with('error', 'Could not update the request.');
        }

        return redirect()->to('/requests/' . $id)->with('message', 'Request updated.');
    }

    public function delete($id = null): ResponseInterface
    {
        $request = $this->findDetailed($id);

        if ($request === null) {
            return redirect()->to('/requests')->with('error', 'Request not found.');
        }

        if (! $this->canManage($request)) {
            return redirect()->to('/requests')->with('error', 'You cannot delete that request.');
        }

        if (! $this->isAdmin() && $request['status'] !== 'pending') {
            return redirect()
                ->to('/requests/' . $request['id'])
                ->with('error', 'Only pending requests can be deleted.');
        }

        if ($request['status'] === 'received') {
            return redirect()
                ->to('/requests/' . $request['id'])
                ->with('error', 'Received requests cannot be deleted because stock was already deducted.');
        }

        if (! $this->requestModel->delete($id)) {
            return redirect()->to('/requests')->with('error', 'Could not delete the request.');
        }

        return redirect()->to('/requests')->with('message', 'Request deleted.');
    }

    public function approve($id = null): ResponseInterface
    {
        return $this->transition($id, 'pending', 'approved', 'Request approved.');
    }

    public function reject($id = null): ResponseInterface
    {
        return $this->transition($id, 'pending', 'rejected', 'Request rejected.');
    }

    public function receive($id = null): ResponseInterface
    {
        $request = $this->findDetailed($id);

        if ($request === null) {
            return redirect()->to('/requests')->with('error', 'Request not found.');
        }

        if ($request['status'] !== 'approved') {
            return redirect()
                ->to('/requests/' . $request['id'])
                ->with('error', 'Request must be approved before it can be received.');
        }

        if ((int) $request['item_stock'] < (int) $request['quantity']) {
            return redirect()
                ->to('/requests/' . $request['id'])
                ->with('error', 'Insufficient stock for this request.');
        }

        $db = db_connect();
        $db->transStart();

        $this->itemModel->update($request['item_id'], [
            'stock' => (int) $request['item_stock'] - (int) $request['quantity'],
        ]);
        $this->requestModel->update($id, ['status' => 'received']);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()
                ->to('/requests/' . $id)
                ->with('error', 'Could not receive the request.');
        }

        return redirect()->to('/requests/' . $id)->with('message', 'Request received and stock updated.');
    }

    public function comment($id = null): ResponseInterface
    {
        $request = $this->findDetailed($id);

        if ($request === null) {
            return redirect()->to('/requests')->with('error', 'Request not found.');
        }

        if (! $this->canView($request)) {
            return redirect()->to('/requests')->with('error', 'You cannot comment on that request.');
        }

        $rules = [
            'body' => 'required|max_length[2000]',
        ];

        if (! $this->validate($rules)) {
            return redirect()
                ->to('/requests/' . $request['id'] . '#comments')
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $body = trim((string) $this->request->getPost('body'));

        if ($body === '') {
            return redirect()
                ->to('/requests/' . $request['id'] . '#comments')
                ->withInput()
                ->with('error', 'Comment cannot be empty.');
        }

        $inserted = $this->commentModel->insert([
            'request_id' => $request['id'],
            'user_id'    => $this->currentUser()['id'],
            'body'       => $body,
        ]);

        if ($inserted === false) {
            return redirect()
                ->to('/requests/' . $request['id'] . '#comments')
                ->withInput()
                ->with('error', 'Could not add comment.');
        }

        return redirect()->to('/requests/' . $request['id'] . '#comments')->with('message', 'Comment added.');
    }

    /**
     * @return array<string, mixed>|null
     */
    private function findDetailed(mixed $id): ?array
    {
        if (! $this->isUuid($id)) {
            return null;
        }

        return $this->requestModel
            ->select('request.*, item.name AS item_name, item.stock AS item_stock, user.name AS user_name, user.email AS user_email')
            ->join('item', 'item.id = request.item_id')
            ->join('user', 'user.id = request.user_id')
            ->where('request.id', $id)
            ->first();
    }

    /**
     * @param array<string, mixed> $request
     */
    private function canView(array $request): bool
    {
        return $this->isAdmin() || $request['user_id'] === $this->currentUser()['id'];
    }

    /**
     * Owners and administrators may change a request.
     *
     * @param array<string, mixed> $request
     */
    private function canManage(array $request): bool
    {
        return $this->canView($request);
    }

    private function transition(mixed $id, string $from, string $to, string $message): ResponseInterface
    {
        $request = $this->findDetailed($id);

        if ($request === null) {
            return redirect()->to('/requests')->with('error', 'Request not found.');
        }

        if ($request['status'] !== $from) {
            return redirect()
                ->to('/requests/' . $request['id'])
                ->with('error', "Request must be {$from} to become {$to}.");
        }

        if (! $this->requestModel->update($id, ['status' => $to])) {
            return redirect()
                ->to('/requests/' . $request['id'])
                ->with('error', 'Could not update the request status.');
        }

        return redirect()->to('/requests/' . $request['id'])->with('message', $message);
    }
}
