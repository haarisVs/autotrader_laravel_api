<?php
namespace App\Repositories\Interface;



interface StockInterface
{
    public function findAll($request);

    public function create(array $attributes);

    public function update($id, array $attributes);

    public function destroy($id);

    public function findById($id);
    public function findAllLocal();
}
