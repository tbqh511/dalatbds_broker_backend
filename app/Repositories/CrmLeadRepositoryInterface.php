<?php

namespace App\Repositories;

interface CrmLeadRepositoryInterface extends RepositoryInterface
{
    public function getLeadsByUserId($userId, $perPage = 10, $filters = []);
}
