<?php
 
namespace App\Models;
 
use CodeIgniter\Model;

 
class BerkasModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'berkas';
	protected $primaryKey           = 'berkas';
	protected $returnType           = 'object';
	protected $allowedFields        = ['id_berkas', 'kdKec', 'kdDesa','kdBS','kdSLS', 'kdJenis', 'berkas', 'tanggalUpload','keterangan'];
	    /**
     * Jangan pakai soft delete, harusnya foreign key bisa bekerja bagus
     */
    protected $useSoftDeletes = false;

    public function getIndexPeta()
    {
        $this->join('jenis_peta', 'berkas.kdJenis = jenis_peta.kdJenis', 'left');
        $this->join('kecamatan', 'berkas.kdKec = kecamatan.kdKec', 'left');
        $this->select('berkas.*, nmKec, nmJenis');
        return  $this->findAll();
    }
            /**
     * Dapatkan semua peta dengan jenis sama
     * @return array[array]
     */
    public function getJenisPeta(array $where = [])
    {
        $this->where($where);
        $this->join('jenis_peta', 'berkas.kdJenis = jenis_peta.kdJenis', 'left');
        $this->join('desa', 'berkas.kdDesa = desa.kdDesa AND berkas.kdKec = desa.kdKec', 'left');
        $this->join('kecamatan', 'berkas.kdKec = kecamatan.kdKec', 'left');
        $this->select('berkas.*, nmKec, nmDesa, CONCAT(6110, '.', berkas.kdKec, berkas.kdDesa, kdBS) AS IDBS');
        $this->orderBy('berkas.kdKec, berkas.kdDesa, berkas.kdBS');
        return  $this->findAll();
    }
    
    /**
     * Cari satu peta saja
     */
    public function getSatuPeta($where)
    {
        $this->where($where);
        //$this->where($where2);
        $this->join('jenis_peta', 'berkas.kdJenis = jenis_peta.kdJenis', 'left');
        $this->join('desa', 'berkas.kdDesa = desa.kdDesa AND berkas.kdKec = desa.kdKec', 'left');
        $this->join('kecamatan', 'berkas.kdKec = kecamatan.kdKec', 'left');
        $this->select('berkas.*, nmKec, nmDesa, CONCAT(6110, '.', berkas.kdKec, berkas.kdDesa, kdBS) AS IDBS');
        $this->orderBy('berkas.kdJenis DESC');
        return  $this->first();
    }
}