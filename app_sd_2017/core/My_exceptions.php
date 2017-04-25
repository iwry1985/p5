<?php
class MY_Exceptions extends CI_Exceptions {

    /**
     * 404 Error Handler
     *
     * @uses    CI_Exceptions::show_error()
     *
     * @param   string  $page       Page URI
     * @param   bool    $log_error  Whether to log the error
     * @return  void
     */
    public function show_404($page = '', $log_error = TRUE)
    {
       
        $CI = &get_instance();
        $CI->output->set_status_header('404');

        $user = cookie_connect();
        $CI->layout->view('errors/404', array(
                                'title' => 'SeriesDOM - 404',
                                'user' => $user
                            ));

        echo $CI->output->get_output();
        exit(4); // EXIT_UNKNOWN_FILE
    }

}