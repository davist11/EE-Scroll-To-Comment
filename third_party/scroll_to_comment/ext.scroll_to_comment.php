<?php
/**
 * Scroll to Comment
 *
 * @package ExpressionEngine
 * @subpackage  Scroll to Comment
 * @category  Extensions
 * @author  Trevor Davis
 * @link  http://trevordavis.net/
 */

/**
 * An ExpressionEngine Extension that causes the page to scroll to a newly submitted
 * comment after submission. This is the EE 2.0 version that has been ported over from
 * Matthew Pennell's version (http://www.thewatchmakerproject.com/blog/new-expressionengine-extension-scroll-to-new-comment)
 */

class Scroll_to_comment_ext {
    
    var $name = 'Scroll to Comment';
    var $version = '1.0.0';
    var $description = '';
    var $settings_exist = 'y';
    var $docs_url = 'http://github.com/davist11/EE-Scroll-To-Comment';
    var $settings = array();
    
    function settings()
    {
      $settings['comment_prefix'] = 'comment';
      return $settings;
    }
    
    
    // Constructor
    function Scroll_to_comment_ext($settings='')
    {
        $this->settings = $settings;
        $this->EE =& get_instance();
    }
    
    // Redirect to the comment after submission
    function redirect_to_latest_comment($data, $comment_moderate, $comment_id)
    {
      
      if ($comment_moderate == 'y')
      {
        
        global $OUT;
        
        $data = array('title' => $this->EE->lang->line('cmt_comment_accepted'),
                      'heading' => $this->EE->lang->line('thank_you'),
                      'content' => $this->EE->lang->line('cmt_will_be_reviewed'),
                      'redirect' => $_POST['RET'],
                      'link' => array($_POST['RET'], $this->EE->lang->line('cmt_return_to_comments')),
                      'rate' => 3
                      );
                      
        $OUT->show_message($data);
      }
      else
      {
        $this->EE->functions->redirect($_POST['RET'] . '#' . $this->settings['comment_prefix'] . $comment_id);
      }

    }
    
    // Activate Extension
    function activate_extension()
    {

      $data = array(
        'class'       => 'Scroll_to_comment_ext',
        'hook'        => 'insert_comment_end',
        'method'      => 'redirect_to_latest_comment',
        'settings'    => serialize($this->settings),
        'priority'    => 10,
        'version'     => $this->version,
        'enabled'     => 'y'
      );

      // insert in database
      $this->EE->db->insert('exp_extensions', $data);

    }

    // Disable Extension
    function disable_extension()
    {
      $this->EE->db->where('class', 'Scroll_to_comment_ext');
      $this->EE->db->delete('exp_extensions');
    }    

}

/* End of file ext.scroll_to_comment.php */
/* Location: ./system/expressionengine/third_party/scroll_to_comment/ext.scroll_to_comment.php */