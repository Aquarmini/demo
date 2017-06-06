<?php

namespace App\Models\RedisModel;

class User extends BaseModel
{
    protected $key = 'redisdmodel:user:{id}';

    protected $type = 'hash';

    protected $fillable = ['id', 'username', 'name'];

    public function replace($primaryKey, $data)
    {
        $info = array_intersect_key($data, array_flip((array)$this->fillable));
        $data = array_merge(array_fill_keys($this->fillable, ''), $info);
        return $this->create($primaryKey, $data);
    }


    public function destroy($primaryKey)
    {
        if (!is_array($primaryKey)) {
            $primaryKey = [$primaryKey];
        }

        return $this->whereIn('id', $primaryKey)->delete();
    }

    public function flushAll()
    {
        return $this->delete();
    }

}