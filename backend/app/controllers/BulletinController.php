<?php

namespace App\Controllers;
use App\Services\BulletinService;
use App\Models\Bulletin;
use Core\Db;

class BulletinController
{
    private $bulletinService;

    public function __construct(BulletinService $bulletinService = null)
    {
        if ($bulletinService) {
            $this->bulletinService = $bulletinService;
        } else {
            $pdo = Db::connection();
            $this->bulletinService = new BulletinService($pdo);
        }
    }

    public function create()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            echo json_encode(['error' => 'Invalid JSON data']);
            return;
        }

        try {
            $bulletin = new Bulletin($input);
            $result = $this->bulletinService->save($bulletin);
            echo json_encode(['message' => 'Bulletin created successfully', 'data' => $result]);
        } catch (\Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function getAll()
    {
        try {
            $bulletins = $this->bulletinService->getAll();
            $bulletinsArray = [];
            foreach ($bulletins as $bulletin) {
                $bulletinsArray[] = $bulletin->toArray();
            }
            echo json_encode(['message' => 'Bulletins retrieved successfully', 'data' => $bulletinsArray]);
        } catch (\Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function getById($id)
    {
        try {
            $bulletin = $this->bulletinService->getById($id);
            if ($bulletin) {
                echo json_encode(['message' => 'Bulletin found', 'data' => $bulletin->toArray()]);
            } else {
                echo json_encode(['error' => 'Bulletin not found']);
            }
        } catch (\Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function update($id)
    {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            echo json_encode(['error' => 'Invalid JSON data']);
            return;
        }

        try {
            $bulletin = new Bulletin($input);
            $result = $this->bulletinService->update($bulletin);
            echo json_encode(['message' => 'Bulletin updated successfully', 'data' => $result]);
        } catch (\Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function delete($id)
    {
        try {
            $this->bulletinService->delete($id);
            echo json_encode(['message' => 'Bulletin deleted successfully']);
        } catch (\Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}