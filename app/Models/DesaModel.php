<?php
 
namespace App\Models;
 
use CodeIgniter\Model;

 
class DesaModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'desa';
	protected $returnType           = 'object';
	protected $allowedFields        = ['kdKec', 'kdDesa','nmDesa'];
	    /**
     * Jangan pakai soft delete, harusnya foreign key bisa bekerja bagus
     */
    protected $useSoftDeletes = false;

	public function getBanyakDesa(array $where = [])
    {
        $this->where($where);
        $this->select('desa.*');
        $this->orderBy('kdKec,KdDesa');
        return  $this->findAll();
    }
	
}