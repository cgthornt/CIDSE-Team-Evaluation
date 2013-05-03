<?php

/**
 * Class to send out email reminders to people.
 *
 * Usage:
 *
 *  // Auto-send out emails
 *  php yiic.php emailreminder
 *
 *  // Preview without sending emails
 *  php yiic.php emailreminder --preview
 *  
 * @author Christopher Thornton
 */
class EmailReminderCommand extends CConsoleCommand {


  /**
   * A list of reminders in the format of:
   *  [student_id] => array:
   *    ['student'] = object
   *    ['evaluations'] = array(eval1, eval2)
   *    ['groups]       = array(group, group)
   * 
   */
  protected $reminders = array();
  
  
  /**
   * Controller for rendering emails
   */
  protected $controller;


  /**
   * Sends email reminders
   * @param boolean $preview if TRUE, only previews the results and doesn't deliver
   */
  public function actionIndex($preview = false) {
    $evaluations = Evaluation::model()
      ->with(array('course.groups'))
      ->where('published = 1 AND due_at < ?', array(new Time));
    
    
    // Loop through each evaluation and associated course group
    foreach($evaluations->findAll() as $eval) {
      foreach($eval->course->groups as $group) {
        
        // Now loop through each student!
        $students = $group->enrolled($eval->published_at);
        foreach($students->findAll() as $student) {
          
          $evalTaken = EvaluationResponseSet::model()->where(array(
            'evaluation_id'   => $eval->id,
            'course_group_id' => $group->id,
            'user_id'         => $student->id,
            'completed'       => 1 ));
          
          // If the evaluation hasn't been taken, then we will add to the list of reminders.
          // Not the most practical implementation if dealing with a large dataset, but oh well!
          if($evalTaken->count() == 0)
            $this->addReminder($student, $eval, $group);
        }
      }
    }
    
    
    // Preview-only
    if($preview) {
      if(count($this->reminders) == 0) {
        echo "No reminders to be sent.\n";
      
      // Send out reminders
      } else {
        foreach($this->reminders as $userId=>$val) {
          echo "Reminders for {$val['student']->fullName}:\n";
          for($i = 0; $i < count($val['evaluations']); $i++) {
            $e = $val['evaluations'][$i]; $g = $val['groups'][$i];
            echo "* {$e->name} (group {$g->name})\n";
          }
          echo "\n";
        }
      }
      
    // Non-preview
    } else {
      foreach($this->reminders as $userId=>$val) {
        $this->deliverEmail($val['student'], $val['evaluations'], $val['groups']);
      }
    }
    
    
    return 0;
  }
  
  
  /**
   * Delivers reminder emails
   */
  protected function deliverEmail(User $student, array $evaluations, array $groups) {
    if($this->controller == null)
      $this->controller = new CController('name');
    
    
    echo "Delivering email to {$student->emailName}... ";

    $html = $this->controller->renderInternal('app/views/emails/reminder.php',
        array('student' => $student, 'evaluations' => $evaluations, 'groups' => $groups),
        true);
    

    // Create a new mailer
    $email = new PHPMailer;
    $email->IsHtml(true);
    
    // From and to
    $email->From = 'donotreply@teamevals.asu.edu';
    $email->AddAddress($student->email, $student->fullName);
    
    // Body
    $email->Body = $html;
    
    // Attempt sending
    if($email->Send()) {
      echo "Message Sent!\n";
    } else {
      echo "Failure!\n";
      echo "\tFailure message: " . $email->ErrorInfo . "\n";
    }
    
  }
  
  /**
   * Adds a reminder to the list of reminders.
   * @param User $student the student that should have a reminder sent
   * @param Evaluation $evaluation the evaluation that needs to be taken
   * @param CourseGroup $group the group that the user is in.
   * @return array the current reminder list for the student
   */
  protected function addReminder(User $student, Evaluation $evaluation, CourseGroup $group) {
    
    $index = (string) $student->id;
    
    // Create format of reminders if not exists!
    if(empty($this->reminders[$index]) || $this->reminders[$index] == null) {
      $this->reminders[$index] = array(
        'student'     => $student,
        'evaluations' => array(),
        'groups'      => array(),
      );
    }
    
    // Push it all!
    $this->reminders[$index]['evaluations'][] = $evaluation;
    $this->reminders[$index]['groups'][] = $group;
    //array_push($reminder['evaluations'], $evaluation);
    //array_push($reminder['groups'], $evaluation);
    //$reminder
    
    // Return the reminder just for fun
    return $this->reminders[$index];
  }
 
  
}