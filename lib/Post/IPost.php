<?php
namespace lib\Post;

interface IPost{

    public function __construct();

    public function create($structure);
    public function delete(array $whereequals);
    public function add(array $values);
    public function update($column,array $values,$to);
}
?>