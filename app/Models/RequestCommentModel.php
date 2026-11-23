<?php

namespace App\Models;

use CodeIgniter\Database\RawSql;
use CodeIgniter\Model;

class RequestCommentModel extends Model
{
    protected $table            = 'request_comment';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['request_id', 'user_id', 'body'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected $beforeInsert = ['generateUuid'];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected function generateUuid(array $eventData): array
    {
        if (empty($eventData['data']['id'])) {
            $eventData['data']['id'] = new RawSql('gen_random_uuid()');
        }

        return $eventData;
    }
}
