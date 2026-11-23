<?php

namespace App\Controllers;

use App\Models\ItemModel;
use App\Models\RequestModel;
use App\Models\UserModel;

class Home extends BaseController
{
    public function index(): string
    {
        $auth         = service('auth');
        $user         = $auth->user();
        $isAdmin      = $auth->isAdmin();
        $itemModel    = new ItemModel();
        $requestModel = new RequestModel();

        $stats = [
            'items'    => $itemModel->countAllResults(),
            'requests' => 0,
            'pending'  => 0,
        ];

        if ($isAdmin) {
            $stats['requests'] = $requestModel->countAllResults();
            $stats['pending']  = $requestModel->where('status', 'pending')->countAllResults();
            $stats['users']    = (new UserModel())->countAllResults();
        } else {
            $stats['requests'] = $requestModel->where('user_id', $user['id'])->countAllResults();
            $stats['pending']  = $requestModel->where('user_id', $user['id'])->where('status', 'pending')->countAllResults();
        }

        $requestModel
            ->select('request.*, item.name AS item_name, user.name AS user_name')
            ->join('item', 'item.id = request.item_id')
            ->join('user', 'user.id = request.user_id');

        if (! $isAdmin) {
            $requestModel->where('request.user_id', $user['id']);
        }

        $recent = $requestModel
            ->orderBy('request.created_at', 'DESC')
            ->limit(5)
            ->findAll();

        return view('home/index', [
            'stats'  => $stats,
            'recent' => $recent,
        ]);
    }
}
