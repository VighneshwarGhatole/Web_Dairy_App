<?php
function paginate($page, $tpages) {
    $adjacents = 2;
    $prevlabel = "&lsaquo; Prev";
    $nextlabel = "Next &rsaquo;";
    $out = "<ul class='pagination'>";
    // previous
    if ($page == 1 || $page == 0) {
        $out.= "<li><span class='previous'>".$prevlabel."</span></li>";
    } else {
        $pageno = $page-1;
        $out.="<li onclick='getdata($pageno)'><a href=\"javascript:void(0)\">".$prevlabel."</a>\n</li>";
    }
    $pmin=($page>$adjacents)?($page - $adjacents):1;
    $pmax=($page<($tpages - $adjacents))?($page + $adjacents):$tpages;
    for ($i = $pmin; $i <= $pmax; $i++) {
        if ($i == $page) {
            $out.= "<li class=\"active\"><a href=\"javascript:void(0)\">".$i."</a></li>\n";
        } else {
            $out.= "<li id='page_$i' onclick='getdata($i)'><a href=\"javascript:void(0)\">".$i. "</a></li>";
        }
    }

    if ($page<($tpages - $adjacents)) {
        $out .= "<li id='page_$tpages' onclick='getdata($tpages)'><a href=\"javascript:void(0)\">".$tpages."</a></li>";
    }
    // next
    if ($page < $tpages) {
        $next = $page+1;
        $out.= "<li onclick='getdata($next)'><a href=\"javascript:void(0)\">".$nextlabel."</a></li>";
    } else {
        $out.= "<li><span class='next'>".$nextlabel."</span></li>";
    }
    $out.= "</ul>";
    return $out;
}
?>
<?php
$totalRecords = isset($arrResponse['totalRecords'])?$arrResponse['totalRecords']:0;
$num_rec_per_page = PAGE_LIMIT;
$total_pages = ceil($totalRecords / $num_rec_per_page);
$page = isset($arrResponse['page'])?$arrResponse['page']:0;
if($total_pages>1){
    echo paginate($page,$total_pages);
}
?>