<?php

namespace App\Controllers;

use App\Models\ItemModel;
use App\Models\RequestModel;
use CodeIgniter\HTTP\ResponseInterface;

class ItemController extends BaseController
{
    private ItemModel $itemModel;

    public function __construct()
    {
        $this->itemModel = new ItemModel();
    }

    public function index(): string
    {
        $q = trim((string) ($this->request->getGet('q') ?? ''));
        $builder = $this->itemModel->orderBy('name', 'ASC');

        if ($q !== '') {
            $builder->groupStart()
                ->like('name', $q, 'both', null, true)
                ->orLike('description', $q, 'both', null, true)
                ->groupEnd();
        }

        return view('items/index', [
            'items' => $builder->findAll(),
            'q'     => $q,
        ]);
    }

    public function create(): string
    {
        return view('items/form', [
            'item'   => null,
            'action' => site_url('items'),
        ]);
    }

    public function store(): ResponseInterface
    {
        $rules = [
            'name'        => 'required|max_length[100]',
            'stock'       => 'required|integer|greater_than_equal_to[0]',
            'description' => 'permit_empty|max_length[1000]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $inserted = $this->itemModel->insert([
            'id'          => $this->uuid(),
            'name'        => trim((string) $this->request->getPost('name')),
            'stock'       => (int) $this->request->getPost('stock'),
            'description' => trim((string) $this->request->getPost('description')),
        ]);

        if ($inserted === false) {
            return redirect()->back()->withInput()->with('error', 'Could not create the item.');
        }

        return redirect()->to('/items')->with('message', 'Item created.');
    }

    public function edit($id = null): ResponseInterface|string
    {
        if (! $this->isUuid($id)) {
            return redirect()->to('/items')->with('error', 'Item not found.');
        }

        $item = $this->itemModel->find($id);

        if ($item === null) {
            return redirect()->to('/items')->with('error', 'Item not found.');
        }

        return view('items/form', [
            'item'   => $item,
            'action' => site_url('items/' . $item['id']),
        ]);
    }

    public function update($id = null): ResponseInterface
    {
        if (! $this->isUuid($id)) {
            return redirect()->to('/items')->with('error', 'Item not found.');
        }

        if ($this->itemModel->find($id) === null) {
            return redirect()->to('/items')->with('error', 'Item not found.');
        }

        $rules = [
            'name'        => 'required|max_length[100]',
            'stock'       => 'required|integer|greater_than_equal_to[0]',
            'description' => 'permit_empty|max_length[1000]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $updated = $this->itemModel->update($id, [
            'name'        => trim((string) $this->request->getPost('name')),
            'stock'       => (int) $this->request->getPost('stock'),
            'description' => trim((string) $this->request->getPost('description')),
        ]);

        if (! $updated) {
            return redirect()->back()->withInput()->with('error', 'Could not update the item.');
        }

        return redirect()->to('/items')->with('message', 'Item updated.');
    }

    public function delete($id = null): ResponseInterface
    {
        if (! $this->isUuid($id)) {
            return redirect()->to('/items')->with('error', 'Item not found.');
        }

        if ($this->itemModel->find($id) === null) {
            return redirect()->to('/items')->with('error', 'Item not found.');
        }

        $requestCount = (new RequestModel())->where('item_id', $id)->countAllResults();

        if ($requestCount > 0) {
            return redirect()
                ->to('/items')
                ->with('error', 'This item has requests and cannot be deleted.');
        }

        if (! $this->itemModel->delete($id)) {
            return redirect()->to('/items')->with('error', 'Could not delete the item.');
        }

        return redirect()->to('/items')->with('message', 'Item deleted.');
    }
}
