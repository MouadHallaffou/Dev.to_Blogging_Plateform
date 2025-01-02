<?php 
namespace App\Src;
require_once __DIR__ . '/Model_Base.php';


class Category extends BaseModel {
    protected $table = 'categories';

    public function createCategory($name) {
        return $this->insertEntry($this->table, ['name' => $name]);
    }

    public function getAllCategory() {
        return $this->selectEntries($this->table);
    }

    public function updateCategory($id, $name) {
        return $this->updateEntry($this->table, ['name' => $name], 'id', $id);
    }

    public function deleteCategory($id) {
        return $this->deleteEntry($this->table, 'id', $id);
    }
}
?>
