<?php
 
namespace App\Models;
 
use CodeIgniter\Model;

 
class KecamatanModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'kecamatan';
	protected $primaryKey           = 'kdKec';
	protected $returnType           = 'object';
	protected $allowedFields        = ['kdKec', 'nmKec'];
	    /**
     * Jangan pakai soft delete, harusnya foreign key bisa bekerja bagus
     */
    protected $useSoftDeletes = false;

	public function getIndexKecamatan()
    {
        $this->select('kecamatan.*');
        return  $this->findAll();
    }
	
}