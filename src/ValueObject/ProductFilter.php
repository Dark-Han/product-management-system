<?php
namespace App\ValueObject;

class ProductFilter{

    private int $page;

    public function __construct(private ?string $name,int $page)
    {
        $this->setPage($page);
    }

    private function setPage(int $page){
        if ($page===0){
            $this->page=1;
        }else{
            $this->page=$page;
        }

    }

    public function getName():?string{
        return $this->name;
    }

    public function getPage():int{
        return $this->page;
    }

}


?>