<?php

namespace App\Models;

use CodeIgniter\Database\RawSql;
use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'user';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'email', 'password', 'is_admin'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected $beforeInsert = ['generateUuid'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Supply the primary key for new records. CI4 requires an explicit
     * primary key when useAutoIncrement is false, so let Postgres
     * generate its value as part of the INSERT.
     */
    protected function generateUuid(array $eventData): array
    {
        if (empty($eventData['data']['id'])) {
            $eventData['data']['id'] = new RawSql('gen_random_uuid()');
        }

        return $eventData;
    }
}
