<?php 
namespace App\Src;
require_once __DIR__ . '/BaseModel.php';


class Tag extends BaseModel {
    protected $table = 'tags';

    public function createTag($name) {
        return $this->insertEntry($this->table, ['name' => $name]);
    }

    public function getAllTags() {
        return $this->selectEntries($this->table);
    }

    public function updateTag($id, $name) {
        return $this->updateEntry($this->table, ['name' => $name], 'id', $id);
    }

    public function deleteTag($id) {
        return $this->deleteEntry($this->table, 'id', $id);
    }
}
?>
