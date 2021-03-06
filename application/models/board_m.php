<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// 모델 데이터베이스 연결 통로
class Board_m extends CI_Model 
{
	function __construct() 
	{
		parent::__construct();
		$sql1 = "ALTER TABLE board AUTO_INCREMENT=1";
		$query = $this->db->query($sql1);
		$sql0 = "SET @COUNT = 0";
		$query = $this->db->query($sql0);
		$sql01 = "UPDATE board SET board_id = @COUNT:=@COUNT+1";
		$query = $this->db->query($sql01);

		$sql2 = "ALTER TABLE comments AUTO_INCREMENT=1";
		$query = $this->db->query($sql2);
		$sql0 = "SET @COUNT = 0";
		$query = $this->db->query($sql0);
		$sql02 = "UPDATE comments SET indexcomments = @COUNT:=@COUNT+1";
		$query = $this->db->query($sql02);

		$sql3 = "ALTER TABLE users AUTO_INCREMENT=1";
		$query = $this->db->query($sql3);
		$sql0 = "SET @COUNT = 0";
		$query = $this->db->query($sql0);
		$sql03 = "UPDATE users SET id = @COUNT:=@COUNT+1";
		$query = $this->db->query($sql03);
	}

	// 게시물 작성자 아이디 반환
	// return string 작성자 아이디
	function writer_check() {
		$table = $this->uri->segment(3);
		$board_id = $this->uri->segment(5);
		
		$sql = "SELECT user_id FROM $table WHERE board_id = '$board_id'";
		$query = $this->db->query($sql);
		
		return $query->row();
	}

	// 게시판 목록
	function get_list($table = 'board', $type = '', $offset = '', $limit = '', $search_word = '') {
		$sword = '';

		// 검색어 있을 경우
		if ($search_word != '') {
			// 검색어를 제목과 내용에서 동일한 문자를 찾아 sql 문제 삽입
			$sword = ' WHERE subject like "%' . $search_word . '%" or contents like "%' . $search_word . '%" ';
		}

		$limit_query = '';

		// 페이징이 있을 경우
		if ($offset != '' OR $limit != '') {
			// 컨트롤러(board)에서 $start의 수를 $offset에 대입 $limit 게시물 수  
			$limit_query = ' LIMIT ' . $offset . ', ' . $limit;
		}

		$sql = "SELECT * FROM $table $sword ORDER BY board_id DESC $limit_query";
		$query = $this->db->query($sql);
		
		if ($type == 'count') {
			$result = $query->num_rows();
		} else {
			$result = $query->result();
		}
		return $result;
	}

	// 게시물 상세보기 가져오기
	// param string $table 게시판 테이블
	// param string $id 게시물 번호
	// return array
	function get_view($table, $id) {
		// 조횟수 증가
		$sql0 = "UPDATE $table SET hits = hits + 1 WHERE board_id='$id'";
		$this->db->query($sql0);
 
		$sql = "SELECT * FROM $table WHERE board_id='$id'";
		$query = $this->db->query($sql);
 
		// 게시물 내용 반환
		$result = $query->row();
 
		return $result;
	}

	// 게시물 입력
	// param array $arrays 테이블 명, 게시물 제목, 게시물 내용 1차 배열
	// return boolean 입력 성공여부
	function insert_board($arrays) {
		extract($arrays);
		$insert_array = array(
			'board_pid' => 0,
			'user_id' => $user_id,
			'user_name' => $user_name,
			'subject' => $subject,
			'contents' => $contents,
			'reg_date' => date("Y-m-d H:i:s")
		);
		
		$result = $this->db->insert($table, $insert_array);
		
		return $result;
	}

	// 게시물 수정
	// param array $arrays 테이블 명, 게시물 번호, 게시물 제목, 게시물 내용
	// return boolean 성공 여부
	function modify_board($arrays) {
		extract($arrays);
		$modify_array = array(
			'subject' => $subject,
			'contents' => $contents
		);
		
		$where = array(
			'board_id' => $board_id
		);
		
		$result = $this->db->update($table, $modify_array, $where);
		
		return $result;
	}

	// 게시물 삭제
	// param string $table 테이블 명
	// param string $no 게시물 번호
	// return boolean 성공 여부
	function delete_content($table, $no) {
		$delete_array = array(
			'board_id' => $no
		);
		$result = $this->db->delete($table, $delete_array);

		return $result;
	}

	// 댓글 입력
	function insert_comment($comments) {
		extract($comments);
		$comment_data = array(
			'comments' => $comment
		);
		$result = $this->db->insert($table, $comment_data);

		return $result;
	}
	
	// 댓글 리스트 가져오기
	// param string $table 테이블 
	// param string $id 게시물 번호
	// return array
	function get_comment($table, $id) {
		$sql = "SELECT * FROM $table WHERE board_pid = '$id' ORDER BY board_id DESC";
		$query = $this->db->query($sql);
		
		$result = $query->result();
		
		return $result;
	}
}