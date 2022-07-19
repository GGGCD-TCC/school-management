<?php
    include '../connection.php';
    if(isset($_POST['submit'])) {
		$subject_teacher = $_POST['subject-teacher'];
		$student = $_POST['student'];
		$grade_value = $_POST['grade-value'];
        $sql = "INSERT INTO grades_attendance (subject_teacher_id, student_id, grade_value) 
		VALUES ($subject_teacher, $student, $grade_value)";
        $query = mysqli_query($connection, $sql);
    }
    else if(isset($_POST['delete'])) {
        $id_selected = $_POST['delete'];
        $sql = "DELETE FROM grades_attendance WHERE id = $id_selected";
        mysqli_query($connection, $sql);
        header("Refresh:0");
    }
?>
<!DOCTYPE html>
<html lang="pt">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css">
        <link rel="stylesheet" href="../css/style_index.css">
        <link rel="shortcut icon" href="../img/icon.ico"/>
        <!--<link rel="stylesheet" href="../css/function.css">!-->
        <title>Cadastrar Notas</title>
        <!--
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor"
            crossorigin="anonymous"
        >
        -->
    </head>
	<header>
        <nav class="navbar">
                <a href="../index.html"><img src="../img/logo.png" class="img"></a>
            <ul>
                <a href="function/function.php"><li>Função</li></a>
                <a href="../employee/employee.php"><li>Funcionário</li></a>
                <a href="../subject/subject.php"><li>Contato</li></a>
                <a href="../grade/grade.php"><li>Ano Escolar</li></a>
                <a href="../period/period.php"><li>Período</li></a>
                <a href="../teacher/teacher.php"><li>Professor</li></a>
                <a href="../classroom/classroom.php"><li>Sala</li></a>
                <a href="../student/student.php"><li>Estudante</li></a>
                <a href="../grades_attendance/grades_attendance.php"><li>Notas</li></a>
            </ul>
        </nav>
    </header>
    <body>
	<div class="main">
        <form method="POST">
            <div class="form" id="insert-form">
				<table>
					<tr class="table-header">
						<th>Cadastrar</th>
					</tr>
					<tr>
						<th>
							<label for="subject-teacher">Professor e Disciplina:</label>
							<select class="myBtn" name="subject-teacher" id="subject-teacher">
								<?php
									$sql = "SELECT subject_teacher.id, teacher.id, teacher.name, subject.name 
									FROM subject_teacher
									INNER JOIN teacher ON teacher.id = subject_teacher.teacher_id
									INNER JOIN subject ON subject.id = subject_teacher.subject_id
									ORDER BY subject_teacher.id";
									$query = mysqli_query($connection, $sql);
									while($column = mysqli_fetch_row($query)) {
										$id = $column[0];
										$teacher_id = $column[1];
										$teacher_name = $column[2];
										$subject = $column[3];
										echo '<option value="'.$id.'">'.$teacher_id.' - '.$teacher_name.' ('.$subject.')</option>';
									}
								?>
							</select><br>
						</th>
					</tr>
					<tr>
						<th>
							<label for="student">Estudante:</label>
							<select class="myBtn" name="student" id="student">
								<?php
									$sql = "SELECT student.id, student.name, grade.name, period.name FROM student
									INNER JOIN classroom ON student.classroom_id = classroom.id
									INNER JOIN grade ON grade.id = classroom.grade_id
									INNER JOIN period ON period.id = classroom.period_id
									ORDER BY student.id";
									$query = mysqli_query($connection, $sql);
									while($column = mysqli_fetch_row($query)) {
										$id = $column[0];
										$student = $column[1];
										$grade = $column[2];
										$period = $column[3];
										echo '<option value="'.$id.'">'.$id.' - '.$student.' ('.$grade.' - '.$period.')</option>';
									}
								?>
							</select><br>
						</th>
					</tr>
					<tr>
						<th>
							<label for="grade-value">Nota:</label>
							<input class="myBtn" type="number" name="grade-value" id="grade-value" min="0" max="10"><br>
                			<input class="myBtn" type="submit" name="submit" value="Enviar">
						</th>
					</tr>
				</table>
            </div>
            <div class="list">
                <table>
                    <tr class="table-header">
						<th>Professor</th>
						<th>Disciplina</th>
						<th>Estudante</th>
                        <th>Nota</th>
						<th>Ações</th>
                    </tr>
                    <tr>
                        <?php
							$sql = "SELECT COUNT(*) FROM grades_attendance";
							$query = mysqli_query($connection, $sql);
							$row = mysqli_fetch_row($query);
							if($row[0] != 0) {
								$sql = "SELECT grades_attendance.id, teacher.id, grades_attendance.student_id, 
								grades_attendance.grade_value, teacher.name, subject.name, student.name,
								grade.name, period.name FROM grades_attendance
								INNER JOIN subject_teacher ON grades_attendance.subject_teacher_id = subject_teacher.id
								INNER JOIN teacher ON teacher.id = subject_teacher.teacher_id
								INNER JOIN subject ON subject.id = subject_teacher.subject_id
								INNER JOIN student ON student.id = grades_attendance.student_id
								INNER JOIN classroom ON student.classroom_id = classroom.id
								INNER JOIN grade ON grade.id = classroom.grade_id
								INNER JOIN period ON period.id = classroom.period_id";
								$query = mysqli_query($connection, $sql);
								while($column = mysqli_fetch_row($query)) {
									$id = $column[0];
									$teacher_id = $column[1];
									$student_id = $column[2];
									$grade_value = $column[3];
									$teacher_name = $column[4];
									$subject_name = $column[5];
									$student_name = $column[6];
									$grade_name = $column[7];
									$period_name = $column[8];
									echo '<tr>';
									echo '<td>'.$teacher_id.' - '.$teacher_name.'</td>';
									echo '<td>'.$subject_name.'</td>';
									echo '<td>'.$student_id.' - '.$student_name.' ('.$grade_name.' - '.$period_name.')</td>';
									echo '<td>'.$grade_value.'</td>';
									echo '<td><button class="myBtn" name="delete" value="'.$id.'">Deletar</button>';
									echo '<a href="edit.php?id='.$id.'"><input class="myBtn" type="button" value="Editar"></a></td>';
									echo '</tr>';
								}
							}
							else {
								echo '<tr><td colspan="4">Não existem notas cadastradas ainda!</td></tr>';
							}
							mysqli_close($connection);
                        ?>
                    </tr>
                </table>
            </div>
        </form>
    </body>
</html>