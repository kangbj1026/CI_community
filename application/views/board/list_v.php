<article id="board_area">
	<table cellpadding="0" cellspacing="0" class="table">
		<thead>
			<tr>
				<th scope="col">번호</th>
				<th scope="col" class="title">제목</th>
				<th scope="col">작성자</th>
				<th scope="col">조회수</th>
				<th scope="col">작성일</th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($list as $lt) {
			?>
				<tr>
					<td class="board_id"><?=$lt->board_id?> </td>
					<td colspan="1"><a rel="external" href="/community/<?=$this->uri->segment(1)?>/view/<?=$this->uri->segment(3)?>/<?=$lt->board_id?>"><?=$lt->subject?></a></td>
					<td colspan="1"><?=$lt->user_id?></td>
					<td colspan="1"><?=$lt->hits?></td>
					<td colspan="1">
						<time datetime="<?=mdate("%Y-%m-%j", human_to_unix($lt->reg_date))?>">
							<?=mdate("%Y-%m-%j", human_to_unix($lt->reg_date))?>
						</time>
					</td>
				</tr>
			<?php
			}
			?>
		</tbody>
		<tfoot>
			<th colspan="5">
				<a href="/community/board/write/<?=$this->uri->segment(3);?>" class="btn" > 쓰기 </a>
				<form id="bd_search" method="post">
					<input type="text" name="search_word" id="q" onkeypress="board_search_enter(document.q);" />
					<button type="button" id="search_btn"> 검색 </button>
				</form>
			</th>
		</tfoot>
	</table>
	<div class="pagination">
		<?=$pagination?>
	</div>
</article>