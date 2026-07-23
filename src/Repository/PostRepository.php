<?php

declare(strict_types=1);

namespace App\Repository;

use Cycle\ORM\Select\Repository;
use Cycle\Database\Injection\Fragment;

class PostRepository extends Repository
{
    public function getPosts(array $relations = [], array $orderBy = [], array $wheres = [])
    {
        $select = $this->select();
        foreach ($relations as $relation) {
            $select->load($relation);
        }

        if (! empty($wheres)) {
            $select->where($wheres);
        }

        if (! empty($orderBy)) {
            $select->orderBy($orderBy);
        }

        return $select;
    }

    public function getStatus(): array
    {
        $total = 0;

        $results =  $this->select()->buildQuery()
            ->columns([
                'status',
                new Fragment('COUNT(id) AS total'),
            ])
            ->groupBy('status')
            ->fetchAll()
        ;

        foreach ($results as $result) {
            $total += $result['total'];
        }

        $results = array_merge([['status' => 'all', 'total' => $total]], $results);

        return array_column($results, 'total', 'status');
    }
}
