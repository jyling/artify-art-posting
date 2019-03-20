<?php
class Package
{
    private $_data;
    public function get($id = null)
    {
        if ($id == null) {
            DB::run()->get('package');
            $this->_data = DB::run()->getResult();
        } else {
            DB::run()->get('package', array('id', '=', $id));
            $this->_data = DB::run()->getResult();
        }
        return $this->_data;
    }

    public function genPackage()
    {
        $read = new Reader();
        $read->read('packages.txt');
        $datas = $this->get();
        $a     = '';
        foreach ($datas as $data => $packages) {
            $a .= $read->modify(array(
                '$cost'    => $packages->cost / 100,
                '$item'    => $packages->package,
                '$item'    => $packages->package,
                '$id'      => $packages->id,
                '$imgPath' => $packages->imgPath,
            ));
        }
        echo $a;
    }

    public function has($id)
    {
        DB::run()->get('package', array('id', '=', $id));
        return (DB::run()->getCount() > 0) ? true : false;
    }
}