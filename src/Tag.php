<?php 
namespace App\Src;

class Tag extends BaseModel {
    protected $table = 'categories';

    public function createTag($name) {
        return $this->insertEntry($this->table, ['name' => $name]);
    }

    public function getAllTag() {
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
