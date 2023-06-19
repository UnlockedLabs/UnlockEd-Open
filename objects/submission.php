<?php
namespace unlockedlabs\unlocked;
require_once 'GUID.php';

/**
 * @brief   Submission Class
 * @details Provides database I/O (CRUD) for the submissions table
 */
class Submission
{

	// database connection and table name
	private $conn;
	private $table_name = "submissions";
	
	// object properties
	public $id;
	public $student_id;
	public $assignment_id;
	public $type;
	public $attempt;
	public $score;
	public $grade;
	public $questions;
	public $submitted_answers;
	public $comments;
	public $submitted;
	
	public function __construct($db)
	{
		$this->conn = $db;
	}
	
	// create the submission
	public function create()
	{

		// to get time-stamp for 'submitted' field
        $this->submitted = date('Y-m-d H:i:s');
		
		// insert query
		$query = "INSERT INTO
					" . $this->table_name . "
				SET
					id=:id,
					student_id=:student_id,
					assignment_id=:assignment_id,
					type=:type,
					attempt=:attempt,
					score=:score,
					grade=:grade,
					questions=:questions,
					submitted_answers=:submitted_answers,
					comments=:comments,
					submitted=:submitted";
	
		// prepare query statement
		$stmt = $this->conn->prepare($query);

		$guid = new GUID();
		$this->id = trim($guid->uuid());

		// sanitize
		$this->student_id=htmlspecialchars(strip_tags($this->student_id));
		$this->assignment_id=htmlspecialchars(strip_tags($this->assignment_id));
		$this->type=htmlspecialchars(strip_tags($this->type));
		$this->attempt=htmlspecialchars(strip_tags($this->attempt));
		$this->score=htmlspecialchars(strip_tags($this->score));
		$this->grade=htmlspecialchars(strip_tags($this->grade));
		$this->questions=htmlspecialchars(strip_tags($this->questions));
		$this->submitted_answers=htmlspecialchars(strip_tags($this->submitted_answers));
		$this->comments=htmlspecialchars($this->comments);
		$this->submitted=htmlspecialchars(strip_tags($this->submitted));
		
		// bind values
		$stmt->bindParam(":id", $this->id);
		$stmt->bindParam(":student_id", $this->student_id);
		$stmt->bindParam(":assignment_id", $this->assignment_id);
		$stmt->bindParam(":type", $this->type);
		$stmt->bindParam(":attempt", $this->attempt);
		$stmt->bindParam(":score", $this->score);
		$stmt->bindParam(":grade", $this->grade);
		$stmt->bindParam(":questions", $this->questions);
		$stmt->bindParam(":submitted_answers", $this->submitted_answers);
		$stmt->bindParam(":comments", $this->comments);
		$stmt->bindParam(":submitted", $this->submitted);
		
		// execute query
		if($stmt->execute() && $stmt->rowCount()){
			
			// //NOTE: may be keeping these $_SESSION variables, just in case ...
			// $_SESSION['quiz_id'] = $this->id;
			// $_SESSION['quiz_name'] = $this->quiz_name;
			return true;	
		}
		
		return false;
	}
	
	// read all submissions from the database
	public function readAll()
	{
		$query = "SELECT
					*
				FROM
					" . $this->table_name . "
				ORDER BY
					submitted ASC";
	
		// prepare query statement
		$stmt = $this->conn->prepare( $query );
	
		// execute query
		$stmt->execute();
	
		return $stmt;
	}
	
	/**
	 * reads one submission by its id and
	 * assigns values to properties student_id, assignment_id,
	 * type, attempt, score, grade, questions, submitted_answers,
	 * comments, and submitted
	 * 
	 * @return record
	 */
	public function readOne()
	{
		$query = "SELECT
					*
				FROM
					" . $this->table_name . "
				WHERE
					id=:id
				LIMIT
					0,1";

		$stmt = $this->conn->prepare( $query );

		// bind selected record id
		$stmt->bindParam(':id', $this->id);
		$stmt->execute();

		// get record details
		$row = $stmt->fetch(\PDO::FETCH_ASSOC);

		// assign values to object properties
		$this->student_id = $row['student_id'];
		$this->assignment_id = $row['assignment_id'];
		$this->type = $row['type'];
		$this->attempt = $row['attempt'];
		$this->score = $row['score'];
		$this->grade = $row['grade'];
		$this->questions = $row['questions'];
		$this->submitted_answers = $row['submitted_answers'];
		$this->comments = $row['comments'];
		$this->submitted = $row['submitted'];
	}

	// read submissions by their assignment id
	public function readSubmissionsByAssignmentId()
	{
		$query = "SELECT
					*
				FROM
					" . $this->table_name . "
				WHERE
					lesson_id=:lesson_id
				ORDER BY
					student_id ASC, submitted ASC";
	
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(':lesson_id', $this->lesson_id);
		$stmt->execute();
	
		return $stmt;
	}

	// read submissions by their student id
	public function readSubmissionsByStudentId()
	{
		$query = "SELECT
					*
				FROM
					" . $this->table_name . "
				WHERE
					student_id=:student_id
				ORDER BY
					student_id ASC, submitted ASC";
	
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(':student_id', $this->student_id);
		$stmt->execute();
	
		return $stmt;
	}

	// read a student's submissions for a particular assignment
	public function readStudentAssignmentSubmissions()
	{
		// select single record query
		$query = "SELECT
					*
				FROM
					" . $this->table_name . "
				WHERE
					student_id=:student_id
				AND
					assignment_id=:assignment_id
				ORDER BY
					attempt ASC";

		// prepare query statement
		$stmt = $this->conn->prepare( $query );

		$stmt->bindParam(":student_id", $this->student_id);
		$stmt->bindParam(':assignment_id', $this->assignment_id);
		$stmt->execute();

		return $stmt;

	}

	/**
     * Read kept submissions of cohort students
     * 
	 * @param guid $cohortId cohort id
	 * 
     * @param string $keptGrade kept grade
     *
     * @return array of course_id, course_name, quiz_id, quiz_name,
	 * student_id, student_name, and student_grades for assignment
     */
	public function readCourseQuizObjectByCohort($cohortId, $keptGrade)
	{

		switch ($keptGrade) {
			case 'highest':
				$query = "SELECT
							courses.id course_id,
							courses.course_name,
							submissions.student_id,
							quizzes.id quiz_id,
							quizzes.quiz_name,
							users.username student_name,
							MAX(grade*1) student_grade
						FROM
							" . $this->table_name . "
						JOIN quizzes ON submissions.assignment_id=quizzes.id
						JOIN lessons ON quizzes.lesson_id=lessons.id
						JOIN courses ON lessons.course_id=courses.id
						JOIN users ON submissions.student_id=users.id
						WHERE
							student_id IN(
							SELECT
								cohort_enrollments.student_id
							FROM
								cohort_enrollments
							WHERE
								cohort_id=:cohort_id
						)
						GROUP BY
							quiz_name, student_id";
				break;
			case 'latest':
				$query = "SELECT
							courses.id course_id,
							courses.course_name,
							submissions.student_id,
							quizzes.id quiz_id,
							quizzes.quiz_name,
							users.username student_name,
							grade student_grade,
							MAX(submitted)
						FROM
							" . $this->table_name . "
						JOIN quizzes ON submissions.assignment_id=quizzes.id
						JOIN lessons ON quizzes.lesson_id=lessons.id
						JOIN courses ON lessons.course_id=courses.id
						JOIN users ON submissions.student_id=users.id
						WHERE
							student_id IN(
							SELECT
								cohort_enrollments.student_id
							FROM
								cohort_enrollments
							WHERE
								cohort_id=:cohort_id
						)
						GROUP BY
							quiz_name, student_id";
				break;
		}

		// prepare query statement
		$stmt = $this->conn->prepare( $query );
		// sanitize
		$cohortId=htmlspecialchars(strip_tags($cohortId));

		$stmt->bindParam(":cohort_id", $cohortId);
		$stmt->execute();

		return $stmt;

	}

	/**
     * Read kept submissions of a student for course
     * 
	 * @param guid $courseId course id
	 * 
     * @param string $keptGrade kept grade
     *
     * @return array of course_id, course_name, quiz_id, quiz_name,
	 * student_id, student_name, and student_grades for assignment
     */
	public function readCQOsForStudentByCourse($courseId, $keptGrade)
	{

		switch ($keptGrade) {
			case 'highest':
				$query = "SELECT
							courses.id course_id,
							courses.course_name,
							submissions.student_id,
							quizzes.id quiz_id,
							quizzes.quiz_name,
							users.username student_name,
							MAX(grade*1) student_grade
						FROM
							" . $this->table_name . "
						JOIN quizzes ON submissions.assignment_id=quizzes.id
						JOIN lessons ON quizzes.lesson_id=lessons.id
						JOIN courses ON lessons.course_id=courses.id
						JOIN users ON submissions.student_id=users.id
						WHERE
							student_id=:student_id
						AND
							course_id=:course_id
						GROUP BY
							quiz_name, student_id";
				break;
			case 'latest':
				$query = "SELECT
							courses.id course_id,
							courses.course_name,
							submissions.student_id,
							quizzes.id quiz_id,
							quizzes.quiz_name,
							users.username student_name,
							grade student_grade,
							MAX(submitted)
						FROM
							" . $this->table_name . "
						JOIN quizzes ON submissions.assignment_id=quizzes.id
						JOIN lessons ON quizzes.lesson_id=lessons.id
						JOIN courses ON lessons.course_id=courses.id
						JOIN users ON submissions.student_id=users.id
						WHERE
							student_id=:student_id
						AND
							course_id=:course_id
						GROUP BY
							quiz_name, student_id";
				break;
		}

		// prepare query statement
		$stmt = $this->conn->prepare( $query );
		// sanitize
		$courseId=htmlspecialchars(strip_tags($courseId));
		$this->student_id=htmlspecialchars(strip_tags($this->student_id));

		$stmt->bindParam(":course_id", $courseId);
		$stmt->bindParam(":student_id", $this->student_id);
		$stmt->execute();

		return $stmt;

	}

	/**
     * Read kept submissions of course students
     * 
	 * @param string $courseId course id
	 * 
     * @param string $keptGrade kept grade
     *
     * @return PDOStatement of course_id, course_name, quiz_id, quiz_name,
	 * student_id, student_name, and student_grades for quizzes in course (course quiz object)
     */
	public function readCourseQuizObjectByCourseId($courseId, $keptGrade)
	{
		switch ($keptGrade) {
			case 'highest':
				$query = "SELECT
						courses.id course_id,
						courses.course_name,
						submissions.student_id,
						quizzes.id quiz_id,
						quizzes.quiz_name,
						users.username student_name,
						MAX(grade*1) student_grade
					FROM
						" . $this->table_name . "
					JOIN quizzes ON submissions.assignment_id=quizzes.id
					JOIN lessons ON quizzes.lesson_id=lessons.id
					JOIN courses ON lessons.course_id=courses.id
					JOIN users ON submissions.student_id=users.id
					WHERE
						submissions.assignment_id IN (
							SELECT
								quizzes.id
							FROM
								quizzes
							WHERE
								quizzes.lesson_id IN (
									SELECT
										lessons.id
									FROM
										lessons
									WHERE
										lessons.course_id=:course_id
								)
						)
					GROUP BY
						quiz_name, student_id";
				break;
			case 'latest':
				$query = "SELECT
						courses.id course_id,
						courses.course_name,
						submissions.student_id,
						quizzes.id quiz_id,
						quizzes.quiz_name,
						users.username student_name,
						grade student_grade,
						MAX(submitted)
					FROM
						" . $this->table_name . "
					JOIN quizzes ON submissions.assignment_id=quizzes.id
					JOIN lessons ON quizzes.lesson_id=lessons.id
					JOIN courses ON lessons.course_id=courses.id
					JOIN users ON submissions.student_id=users.id
					WHERE
						submissions.assignment_id IN (
							SELECT
								quizzes.id
							FROM
								quizzes
							WHERE
								quizzes.lesson_id IN (
									SELECT
										lessons.id
									FROM
										lessons
									WHERE
										lessons.course_id=:course_id
								)
						)
					GROUP BY
						quiz_name, student_id";
				break;
		}
	
		$stmt = $this->conn->prepare( $query );
		$courseId=htmlspecialchars(strip_tags($courseId));
		$stmt->bindParam(':course_id', $courseId);
		$stmt->execute();
	
		return $stmt;
	}

	/**
     * Read kept submissions of school students
     * 
	 * @param string $catId category id
	 * 
     * @param string $keptGrade kept grade
     *
     * @return PDOStatement of course_id, course_name, quiz_id, quiz_name,
	 * student_id, student_name, and student_grades for quizzes in course (course quiz object)
     */
	public function readCourseQuizObjectByCatId($catId, $keptGrade)
	{
		switch ($keptGrade) {
			case 'highest':
				$query = "SELECT
						courses.id course_id,
						courses.course_name,
						submissions.student_id,
						quizzes.id quiz_id,
						quizzes.quiz_name,
						users.username student_name,
						MAX(grade*1) student_grade
					FROM
						" . $this->table_name . "
					JOIN quizzes ON submissions.assignment_id=quizzes.id
					JOIN lessons ON quizzes.lesson_id=lessons.id
					JOIN courses ON lessons.course_id=courses.id
					JOIN topics ON courses.topic_id=topics.id
					JOIN categories ON topics.category_id=categories.id
					JOIN users ON submissions.student_id=users.id
					WHERE
						submissions.assignment_id IN (
							SELECT
								quizzes.id
							FROM
								quizzes
							WHERE
								quizzes.lesson_id IN (
									SELECT
										lessons.id
									FROM
										lessons
									WHERE
										lessons.course_id IN (
											SELECT
												courses.id
											FROM
												courses
											WHERE
												courses.topic_id IN (
													SELECT
														topics.id
													FROM
														topics
													WHERE
														topics.category_id IN (
															SELECT
																categories.id
															FROM
																categories
															WHERE
																categories.id=:category_id
														)
												)
										)
								)
						)
					GROUP BY
						quiz_name, student_id";
				break;
			case 'latest':
				$query = "SELECT
						courses.id course_id,
						courses.course_name,
						submissions.student_id,
						quizzes.id quiz_id,
						quizzes.quiz_name,
						users.username student_name,
						grade student_grade,
						MAX(submitted)
					FROM
						" . $this->table_name . "
					JOIN quizzes ON submissions.assignment_id=quizzes.id
					JOIN lessons ON quizzes.lesson_id=lessons.id
					JOIN courses ON lessons.course_id=courses.id
					JOIN topics ON courses.topic_id=topics.id
					JOIN categories ON topics.category_id=categories.id
					JOIN users ON submissions.student_id=users.id
					WHERE
						submissions.assignment_id IN (
							SELECT
								quizzes.id
							FROM
								quizzes
							WHERE
								quizzes.lesson_id IN (
									SELECT
										lessons.id
									FROM
										lessons
									WHERE
										lessons.course_id IN (
											SELECT
												courses.id
											FROM
												courses
											WHERE
												courses.topic_id IN (
													SELECT
														topics.id
													FROM
														topics
													WHERE
														topics.category_id IN (
															SELECT
																categories.id
															FROM
																categories
															WHERE
																categories.id=:category_id
														)
												)
										)
								)
						)
					GROUP BY
						quiz_name, student_id";
				break;
		}
	
		$stmt = $this->conn->prepare( $query );
		$courseId=htmlspecialchars(strip_tags($catId));
		$stmt->bindParam(':category_id', $catId);
		$stmt->execute();
	
		return $stmt;
	}

	/**
     * Read submissions by their course id
     * 
	 * @param string $courseId course id
	 * 
     * @return PDOStatement
     */
	public function readSubmissionsByCourseId($courseId)
	{	
		$query = "SELECT
					*
				FROM
					" . $this->table_name . "
				WHERE
					submissions.assignment_id IN (
						SELECT
							quizzes.id
						FROM
							quizzes
						WHERE
							quizzes.lesson_id IN (
								SELECT
									lessons.id
								FROM
									lessons
								WHERE
									lessons.course_id=:course_id
							)
					)
				ORDER BY
					student_id ASC, submitted ASC";
	
		$stmt = $this->conn->prepare( $query );
		$courseId=htmlspecialchars(strip_tags($courseId));
		$stmt->bindParam(':course_id', $courseId);
		$stmt->execute();
	
		return $stmt;
	}

	/**
     * Read all quiz submissions by their cohort id
     * 
	 * @param string $cohortId cohort id
	 * 
     * @return PDOStatement
     */
	public function readSubmissionsByCohortId($cohortId)
	{	
		$query = "SELECT
					*
				FROM
					" . $this->table_name . "
				WHERE
				student_id IN(
				SELECT
					cohort_enrollments.student_id
				FROM
					cohort_enrollments
				WHERE
					cohort_id=:cohort_id
				)";
	
		$stmt = $this->conn->prepare( $query );
		$courseId=htmlspecialchars(strip_tags($courseId));
		$stmt->bindParam(':course_id', $courseId);
		$stmt->execute();
	
		return $stmt;
	}

	// update the submission
	public function update()
	{
		$query = "UPDATE
					" . $this->table_name . "
				SET
					student_id=:student_id,
					assignment_id=:assignment_id,
					type=:type,
					attempt=:attempt,
					score=:score,
					grade=:grade,
					questions=:questions,
					submitted_answers=:submitted_answers,
					comments=:comments,
					submitted=:submitted
				WHERE
					id=:id";

		// sanitize
		$this->student_id=htmlspecialchars(strip_tags($this->student_id));
		$this->assignment_id=htmlspecialchars(strip_tags($this->assignment_id));
		$this->type=htmlspecialchars(strip_tags($this->type));
		$this->attempt=htmlspecialchars(strip_tags($this->attempt));
		$this->score=htmlspecialchars(strip_tags($this->score));
		$this->grade=htmlspecialchars(strip_tags($this->grade));
		$this->questions=htmlspecialchars(strip_tags($this->questions));
		$this->submitted_answers=htmlspecialchars(strip_tags($this->submitted_answers));
		$this->comments=htmlspecialchars($this->comments);
		$this->submitted=htmlspecialchars(strip_tags($this->submitted));
		
		$stmt = $this->conn->prepare($query);

		// bind values
		// is it necessary to sanitize first?
		$stmt->bindParam(":student_id", $this->student_id);
		$stmt->bindParam(":assignment_id", $this->assignment_id);
		$stmt->bindParam(":type", $this->type);
		$stmt->bindParam(":attempt", $this->attempt);
		$stmt->bindParam(":score", $this->score);
		$stmt->bindParam(":grade", $this->grade);
		$stmt->bindParam(":questions", $this->questions);
		$stmt->bindParam(":submitted_answers", $this->submitted_answers);
		$stmt->bindParam(":comments", $this->comments);
		$stmt->bindParam(":submitted", $this->submitted);

		// execute the query
		if($stmt->execute() && $stmt->rowCount()){
			return true;
		}

		return false;
	}
	
	// delete the submission
	public function delete()
	{
		$query = "DELETE FROM
					" . $this->table_name . "
				WHERE
					id=:id";

		// sanitize
		$this->id=htmlspecialchars(strip_tags($this->id));
					
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':id', $this->id);

		if($stmt->execute() && $stmt->rowCount()){
			return true;
		}

		return false;
	}

	// delete submissions by assignment id
	public function deleteSubmissionsByAssignmentId()
	{
		$query = "DELETE FROM
					" . $this->table_name . "
				WHERE
					assignment_id=:assignment_id";

		// sanitize
		$this->lesson_id=htmlspecialchars(strip_tags($this->assignment_id));
					
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':assignment_id', $this->assignment_id);

		if($stmt->execute() && $stmt->rowCount()){
			return true;
		}

		return false;
	}

	// delete submissions by student id
	public function deleteSubmissionsByStudentId()
	{
		$query = "DELETE FROM
					" . $this->table_name . "
				WHERE
					student_id=:student_id";

		// sanitize
		$this->student_id=htmlspecialchars(strip_tags($this->student_id));
					
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':student_id', $this->student_id);

		if($stmt->execute() && $stmt->rowCount()){
			return true;
		}

		return false;
	}

	// count all submissions
	public function countAll()
	{
		// query to count all data
		$query = "SELECT
					COUNT(*) AS total_rows
				FROM
					" . $this->table_name . "";

		// prepare query statement
		$stmt = $this->conn->prepare($query);
		$stmt->execute();

		$row = $stmt->fetch(\PDO::FETCH_ASSOC);
		$total_rows = $row['total_rows'];

		return $total_rows;
	}

	// count submissions by assignment id
	public function countByAssignmentId()
	{
		// query to count all data
		$query = "SELECT
					COUNT(*) AS total_rows
				FROM
					" . $this->table_name . "
				WHERE
					assignment_id=:assignment_id";

		// prepare query statement
		$stmt = $this->conn->prepare($query);

		$stmt->bindParam(':assignment_id', $this->assignment_id);
		$stmt->execute();

		$row = $stmt->fetch(\PDO::FETCH_ASSOC);
		$total_rows = $row['total_rows'];

		return $total_rows;
	}

	// count submissions by student id
	public function countByStudentId()
	{
		// query to count all data
		$query = "SELECT
					COUNT(*) AS total_rows
				FROM
					" . $this->table_name . "
				WHERE
					student_id=:student_id";

		// prepare query statement
		$stmt = $this->conn->prepare($query);

		$stmt->bindParam(':student_id', $this->student_id);
		$stmt->execute();

		$row = $stmt->fetch(\PDO::FETCH_ASSOC);
		$total_rows = $row['total_rows'];

		return $total_rows;
	}

	// count submissions by course id
	public function countByCourseId()
	{
		$query = "SELECT
					COUNT(*) AS total_submissions
				FROM
					" . $this->table_name . "
				WHERE
					submissions.assignment_id IN (
						SELECT
							quizzes.id
						FROM
							quizzes
						WHERE
							quizzes.lesson_id IN (
								SELECT
									lessons.id
								FROM
									lessons
								WHERE
									lessons.course_id=:course_id
							)
					)";

		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(':course_id', $this->course_id);
		$stmt->execute();

		$row = $stmt->fetch(\PDO::FETCH_ASSOC);
		$total_submissions = $row['total_submissions'];

		return $total_submissions;
	}

	// check if submission exists from student for a particular assignment
	public function attemptNumber()
	{
		// select single record query
		$query = "SELECT
					COUNT(id) AS attempts
				FROM
					" . $this->table_name . "
				WHERE
					student_id=:student_id
				AND
					assignment_id=:assignment_id";

		// prepare query statement
		$stmt = $this->conn->prepare( $query );

		$stmt->bindParam(":student_id", $this->student_id);
		$stmt->bindParam(':assignment_id', $this->assignment_id);
		$stmt->execute();

		// get record details
		$row = $stmt->fetch(\PDO::FETCH_ASSOC);
		extract($row);

		return $attempts;

	}

}
