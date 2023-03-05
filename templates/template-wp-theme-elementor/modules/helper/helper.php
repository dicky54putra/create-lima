<?php
namespace GlobalHelper;

defined( 'ABSPATH' ) || die( "Can't access directly" );

class Helper
{
	/*
	 * example how to call in your ph
	 * use GlobalHelper\Helper
	 * $pagination = Helper::paginationWPDefault($dataPagination);
	 * echo $pagination;
	*/

	/**
	 * @param
	 * $dataPagination = [
	 *      'id'            => 'pagi-2',
	 *      'totalPage'     => $total_pages,
	 *      'currentPage'   => $page,
	 *      'prevText'      => 'Previous',
	 *      'nextText'      => 'Next',
	 *      'baseUrl'       => get_the_permalink()
	 *  ];
	 */

	// pagination ori wp
	public static function paginationWPDefault($data): string
	{
		$pagination = '<div id="'.$data['id'].'" class="pagination">';
		$pagination .= paginate_links([
			'total'              => $data['totalPage'],
			'current'            => $data['currentPage'],
			'next_text'          => __($data['nextText']),
			'prev_text'          => __($data['prevText']),
		]);
		$pagination .= '</div>';

		return $pagination;
	}

	// pagination custom
	public static function paginationCustomNoAjax($data): string
	{
		$pagination = '';
		if ($data['totalPage'] > 1){
			$pagination = '<div id="'.$data['id'].'" 
				perPage="'.$data['perPage'].'" 
				totalPage="'.$data['totalPage'].'" 
				offset="'.$data['offset'].'"  
				baseUrl="'.$data['baseUrl'].'" 
				class="pagination">';

			if ($data['currentPage'] > 1){
				$pagination .= '<a href="'.$data['baseUrl'].'page/' .($data['currentPage'] -1). '/" page ="'.($data['currentPage']-1).'"  class="prev page-numbers">'.$data['prevText'].'</a>';
			}

			for ($i = 1; $i <= $data['totalPage']; $i++){

				// Style selain current == 1 atau current == last
				if ($data['currentPage'] == $i && $data['currentPage'] !== 1 && $data['currentPage'] !== $data['totalPage'] && $data['totalPage'] > 2){

					$first  = '1';
					$last   = $data['totalPage'];
					$before = $data['currentPage']-1;
					$after  = $data['currentPage']+1;

					if ($data['currentPage'] > 1 && $data['currentPage'] < $last-1 && $data['totalPage'] !== 3){

						$pagination .= '<a href="'.$data['baseUrl'].'" page ="'.$first.'"  class="page-numbers">'.$first.'</a>';
						if ($data['currentPage']-1 !== 1 && $data['currentPage']+1 !== 2){
							$pagination .= '<span class="page-numbers dots">...</span>';
							$pagination .= '<a href="'.$data['baseUrl'].'page/' .$before. '/" page ="'.$before.'"  class="page-numbers">'.$before.'</a>';
						}
						$pagination .= '<span href="'.$data['baseUrl'].'" page="' .$i. '" class="page-numbers current">'.$i.'</span>';
						if ($data['currentPage']+1 !== $data['totalPage']){
							$pagination .= '<a href="'.$data['baseUrl'].'page/' .$after. '/" page ="'.$after.'"  class="page-numbers">'.$after.'</a>';
							$pagination .= '<span class="page-numbers dots">...</span>';
						}
						$pagination .= '<a href="'.$data['baseUrl'].'page/'.$last.'" page ="'.$last.'"  class="page-numbers">'.$last.'</a>';
						$pagination .= '<a href="'.$data['baseUrl'].'page/'.$after.'" page ="'.$after.'" class="next page-numbers">'.$data['nextText'].'</a>';
					}

					if ($data['currentPage'] > 1 && $data['currentPage'] == $last-1  && $data['totalPage'] > 2 && $data['totalPage'] !== 3){
						$pagination .= '<a href="'.$data['baseUrl'].'" page ="'.$first.'" class="page-numbers">'.$first.'</a>';
						if ($data['currentPage']-1 !== 1 && $data['currentPage']+1 !== 2){
							$pagination .= '<span class="page-numbers dots">...</span>';
							$pagination .= '<a href="'.$data['baseUrl'].'page/' .$before. '/" page ="'.$before.'" class="page-numbers">'.$before.'</a>';
						}
						$pagination .= '<span href="'.$data['baseUrl'].'" page="' .$i. '" class="page-numbers current">'.$i.'</span>';

						$pagination .= '<a href="'.$data['baseUrl'].'page/'.$last.'" page ="'.$last.'" class="page-numbers">'.$last.'</a>';
						$pagination .= '<a href="'.$data['baseUrl'].'page/'.$after.'" page ="'.$after.'" class="next page-numbers">'.$data['nextText'].'</a>';
					}

				}

				// jika current == last
				if ($data['currentPage'] == $i && $data['currentPage'] == $data['totalPage']  && $data['totalPage'] > 3){

					$first  = '1';
					$before = $data['currentPage']-1;
					$before2 = $data['currentPage']-2;


					$pagination .= '<a href="'.$data['baseUrl'].'" page ="'.$first.'" class="page-numbers">'.$first.'</a>';
					$pagination .= '<span class="page-numbers dots">...</span>';
					$pagination .= '<a href="'.$data['baseUrl'].'page/' .$before2. '/" page ="'.$before2.'" class="page-numbers">'.$before2.'</a>';
					$pagination .= '<a href="'.$data['baseUrl'].'page/' .$before. '/" page ="'.$before.'" class="page-numbers">'.$before.'</a>';
					$pagination .= '<span href="'.$data['baseUrl'].'" page="' .$i. '" class="page-numbers current">'.$i.'</span>';


				}

				// jika current == first
				if ($data['currentPage'] == $i && $i == 1  && $data['totalPage'] > 3){

					$last   = $data['totalPage'];
					$after  = $data['currentPage']+1;
					$after2  = $data['currentPage']+2;

					$pagination .= '<span href="'.$data['baseUrl'].'" page="' .$i. '" class="page-numbers current">'.$i.'</span>';
					$pagination .= '<a href="'.$data['baseUrl'].'page/' .$after. '/" page ="'.$after.'" class="page-numbers">'.$after.'</a>';
					$pagination .= '<a href="'.$data['baseUrl'].'page/' .$after2. '/" page ="'.$after2.'" class="page-numbers">'.$after2.'</a>';
					$pagination .= '<span class="page-numbers dots">...</span>';
					$pagination .= '<a href="'.$data['baseUrl'].'page/'.$last.'" page ="'.$last.'" class="page-numbers">'.$last.'</a>';
					$pagination .= '<a href="'.$data['baseUrl'].'page/'.$after.'" page ="'.$after.'" class="next page-numbers">'.$data['nextText'].'</a>';

				}

				// jika total page == 3 dan cur page == 1
				if ($data['currentPage'] == $i && $i == 1 && $data['totalPage'] == 3){
					$last   = $data['totalPage'];
					$after  = $data['currentPage']+1;

					$pagination .= '<span href="'.$data['baseUrl'].'" page="' .$i. '" class="page-numbers current">'.$i.'</span>';
					$pagination .= '<a href="'.$data['baseUrl'].'page/' .$after. '/" page ="'.$after.'" class="page-numbers">'.$after.'</a>';
					$pagination .= '<a href="'.$data['baseUrl'].'page/'.$last.'" page ="'.$last.'" class="page-numbers">'.$last.'</a>';
					$pagination .= '<a href="'.$data['baseUrl'].'page/'.$after.'" page ="'.$after.'" class="next page-numbers">'.$data['nextText'].'</a>';

				}

				// jika total page == 3 dan cur page == last
				if ($data['currentPage'] == $i && $data['currentPage'] == $data['totalPage']  && $data['totalPage'] == 3){

					$first  = '1';
					$before = $data['currentPage']-1;

					$pagination .= '<a href="'.$data['baseUrl'].'" page ="'.$first.'" class="page-numbers">'.$first.'</a>';
					$pagination .= '<a href="'.$data['baseUrl'].'page/' .$before. '/" page ="'.$before.'" class="page-numbers">'.$before.'</a>';
					$pagination .= '<span href="'.$data['baseUrl'].'" page="' .$i. '" class="page-numbers current">'.$i.'</span>';
				}


				// jika current == first && current page == 1 && total page == 2
				if ($data['currentPage'] == $i && $i == 1  && $data['totalPage'] == 2){

					$last   = $data['totalPage'];
					$after  = $data['currentPage']+1;

					$pagination .= '<span href="'.$data['baseUrl'].'" page="' .$i. '" class="page-numbers current">'.$i.'</span>';
					$pagination .= '<a href="'.$data['baseUrl'].'page/'.$last.'" page ="'.$last.'" class="page-numbers">'.$last.'</a>';
					$pagination .= '<a href="'.$data['baseUrl'].'page/'.$after.'" page ="'.$after.'" class="next page-numbers">'.$data['nextText'].'</a>';

				}

				// jika current == first && current page == 2 && total page == 2
				if ($data['currentPage'] == $i && $i == 2  && $data['totalPage'] == 2){

					$first  = '1';

					$pagination .= '<a href="'.$data['baseUrl'].'" page ="'.$first.'" class="page-numbers">'.$first.'</a>';
					$pagination .= '<span href="'.$data['baseUrl'].'" page="' .$i. '" class="page-numbers current">'.$i.'</span>';

				}

			}

			$pagination .= '</div>';

		}

		return $pagination;
	}
}