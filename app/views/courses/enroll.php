<?php
// Global HTML and CSS files
Html::globalCssFile('student_enrollment');
Html::globalScriptFile('student_enrollment');

echo $this->pageTitle("Students");
$this->breadcrumbs=array(
	'My Courses'=>array('index'),
	$course->name=>array('view','id'=>$course->id),
	'Students',
);

$this->renderPartial('_menuView', array('course' => $course));

// Some anonymous functions for showing a student

global $c;
$c = $this;

function showGroup(CourseGroup $group, $form) {
  global $c;
  return $c->renderPartial('enroll/_group', array('group' => $group, 'form' => $form));
}

function showStudent(User $student, CourseGroup $group, $form) {
  global $c;
  return $c->renderPartial('enroll/_student_item', array('group' => $group, 'student' => $student));
  
}

?>

<p>
	You may drag students from one team to another. Import students to the right.
</p>
	
<!-- Import Student -->
<div class="span4 well well-small" style="margin-left: 15px; padding-bottom:0;float:right;padding-top:3px">
	<h3>Import Students</h3>
	<p>
		Select an Excel file to import students. 
	</p>
	<p>
		<!-- Upload Form Here -->
		<?php echo Html::beginForm(array('courses/importStudent'), 'post', array('enctype' => 'multipart/form-data')); ?>
			<input type="file" name='importStudentFile' style="line-height: normal">
			<div class="form-actions" style="text-align: right;margin-bottom: 0;padding-bottom: 0px;margin-top:4px">
			<input type="submit" class='btn btn-primary btn-small' value="Import Students">
				</div>
		<?php echo Html::endForm(); ?>
	</p>
</div>

  
<?php
$form = $this->beginWidget('CActiveForm', array(
	'id'=>'enroll-form',
	'enableAjaxValidation'=> false,
));
?>
<div class="row">
	<!-- Current Teams -->
	<div class="span6">
		<h3>Student Teams</h3>
		<div class="alert alert-info" style="margin-top:15px">
			Changes will not be saved until you press "submit"
		</div>
		<div id="group-placeholder" style="display: none">
			<?php
				$group = new CourseGroup;
				$group->id = "PLACEHOLDER";
				echo showGroup($group, $form);
			?>
		</div>
		
		<div id="group-existing">
		<?php foreach($course->groups as $group) echo showGroup($group, $form); ?>
		</div>
		
		<div id="group-new-group">
		</div>
		
		<p>
		<a href="#" id="new-group">New Group</a>
	</p>
	</div>
</div>
  
  
  <div class="form-actions">
    <?php echo Html::link('Cancel', array('courses/view', 'id' => $course->id), array('class' => 'btn')); ?>
    <?php echo Html::submitButton('Save', array('class' => 'btn btn-primary'))  ?>
  </div>

<?php $this->endWidget(); ?>
<div style="clear: both"></div>