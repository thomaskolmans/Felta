<?php
namespace lib\post;

interface IPost{

    public function __construct();

    public function create($structure);
    public function delete(array $whereequals);
    public function update($column,array $values,$to);
}
?>