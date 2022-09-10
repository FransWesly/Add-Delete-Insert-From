<?php
// from set
class Pagination{
    protected $baseURL        = '';
    protected $totalRows      = '';
    protected $perPage        = 10;
    protected $numLinks       = 2;
    protected $currentPage    =  0;
    protected $firstLink      = 'Beranda';
    protected $nextLink       = 'Berikutnya &raquo;';
    protected $prevLink       = '&laquo; Kembali';
    protected $lastLink       = 'Last';
    protected $fullTagOpen    = '<div class="pagination">';
    protected $fullTagClose   = '</div>';
    protected $firstTagOpen   = '';
    protected $firstTagClose  = '&nbsp;';
    protected $lastTagOpen    = '&nbsp;';
    protected $lastTagClose    = '';
    protected $curTagOpen    = '&nbsp;<b>';
    protected $curTagClose    = '</b>';
    protected $nextTagOpen    = '&nbsp;';
    protected $nextTagClose    = '&nbsp;';
    protected $prevTagOpen    = '&nbsp;';
    protected $prevTagClose    = '';
    protected $numTagOpen    = '&nbsp;';
    protected $numTagClose    = '';
    protected $showCount    = true;
    protected $currentOffset= 0;
    protected $queryStringSegment = 'page';
    
    function __construct($params = array()){
        if (count($params) > 0){
            $this->initialize($params);        
        }
    }
    
    function initialize($params = array()){
        if (count($params) > 0){
            foreach ($params as $key => $val){
                if (isset($this->$key)){
                    $this->$key = $val;
                }
            }        
        }
    }
    // menetukan Baris dari tabel    
    function createLinks(){ 
        if ($this->totalRows == 0 OR $this->perPage == 0){
           return '';
        }
        // penomoran
        $numPages = ceil($this->totalRows / $this->perPage);
        // Is there only one page? will not need to continue
        if ($numPages == 1){
            if ($this->showCount){
                $info = 'Hasil : ' . $this->totalRows . ' data ditemukan';
                return $info;
            }else{
                return '';
            }
        }
        
        // string
        $query_string_sep = (strpos($this->baseURL, '?') === FALSE) ? '?page=' : '&amp;page=';
        $this->baseURL = $this->baseURL.$query_string_sep;
        
        $this->currentPage = isset($_GET[$this->queryStringSegment])?$_GET[$this->queryStringSegment]:0;
        
        if (!is_numeric($this->currentPage) || $this->currentPage == 0){
            $this->currentPage = 1;
        }
        
        $output = '';
        
        // notification
        if ($this->showCount){
           $currentOffset = ($this->currentPage > 1)?($this->currentPage - 1)*$this->perPage:$this->currentPage;
           $info = 'Menampilkan data ke-' . $currentOffset. ' hingga ' ;
        
           if( ($currentOffset + $this->perPage) <= $this->totalRows )
              $info .= $this->currentPage * $this->perPage;
           else
              $info .= $this->totalRows;
        
           $info .= ', dari ' . $this->totalRows . ' data | ';
        
           $output .= $info;
        }
        
        $this->numLinks = (int)$this->numLinks;
        
        // penomoran page
        if($this->currentPage > $this->totalRows){
            $this->currentPage = $numPages;
        }
        
        $uriPageNum = $this->currentPage; 
        $start = (($this->currentPage - $this->numLinks) > 0) ? $this->currentPage - ($this->numLinks - 1) : 1;
        $end   = (($this->currentPage + $this->numLinks) < $numPages) ? $this->currentPage + $this->numLinks : $numPages;

        if($this->currentPage > $this->numLinks){
            $firstPageURL = str_replace($query_string_sep,'',$this->baseURL);
            $output .= $this->firstTagOpen.'<a href="'.$firstPageURL.'" class="btn btn-success">'.$this->firstLink.'</a>'.$this->firstTagClose;
        }
        if($this->currentPage != 1){
            $i = ($uriPageNum - 1);
            if($i == 0) $i = '';
            $output .= $this->prevTagOpen.'<a  title="Lihat Data Sebelumnya" href="'.$this->baseURL.$i.'" class="btn btn-info">'.$this->prevLink.'</a>'.$this->prevTagClose;
        }
        for($loop = $start -1; $loop <= $end; $loop++){
            $i = $loop;
            if($i >= 1){
                if($this->currentPage == $loop){
                    $output .= $this->curTagOpen.$loop.$this->curTagClose;
                }else{
                    $output .= $this->numTagOpen.'<a href="'.$this->baseURL.$i.'" class="btn btn-default">'.$loop.'</a>'.$this->numTagClose;
                }
            }
        }
        if($this->currentPage < $numPages){
            $i = ($this->currentPage + 1);
            $output .= $this->nextTagOpen.'<a title="Lihat Data Selanjutnya" href="'.$this->baseURL.$i.'" class="btn btn-info">'.$this->nextLink.'</a>'.$this->nextTagClose;
        }
        if(($this->currentPage + $this->numLinks) < $numPages){
            $i = $numPages;
            $output .= $this->lastTagOpen.'<a href="'.$this->baseURL.$i.'">'.$this->lastLink.'</a>'.$this->lastTagClose;
        }
        $output = preg_replace("#([^:])//+#", "\\1/", $output);
        // Add the wrapper HTML if exists
        $output = $this->fullTagOpen.$output.$this->fullTagClose;
        
        return $output;        
    }
}