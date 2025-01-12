<?php

namespace App\Src\Model;

class Paging
{
    public int $page;
    public int $pageSize;
    public bool $more;
    public int $total;
}
