<?php

    /**
     *
     * Copyright (C) 2010, 2011 Tom Kaczocha <freedomdeveloper@yahoo.com>
     *
     * This file is part of UnitCheck.
     *
     * UnitCheck is free software: you can redistribute it and/or modify
     * it under the terms of the GNU General Public License as published by
     * the Free Software Foundation, either version 3 of the License, or
     * (at your option) any later version.
     *
     * UnitCheck is distributed in the hope that it will be useful,
     * but WITHOUT ANY WARRANTY; without even the implied warranty of
     * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     * GNU General Public License for more details.
     *
     * You should have received a copy of the GNU General Public License
     * along with UnitCheck.  If not, see <http://www.gnu.org/licenses/>.
     *
     */

    /**
     * UnitCheckUser class is a template for UnitCheck User objects.
     *
     * Copyright 	(c) 2010, 2011 Tom Kaczocha
     *
     * @package
     * @author		Tom Kaczocha <freedomdeveloper@yahoo.com>
     * @copyright	2010 Tom Kaczocha
     * @license         GNU General Public License, version 3.0
     * @version 	1.0
     * @access		public
     */
    class UnitCheckUser {

        private $_userID;
        private $_user_first_name;
        private $_user_last_name;
        private $_email;
        private $_password;
        private $_mainProject;
        private $_userIsLoggedIn;
        

        public function __construct($id = 0) {
            global $session;

            $this->checkUserLogin();
            $this->initUser($this->_userID);

            // check for matching session in database
            $session_status = $session->checkForSession();

            if ($session_status) {// match -> update session object
                $this->updateSession();
            }
            else { // no match -> create new session object
                $session->setNewSession();
            }
        }

        public function __destruct() {
            
        }

        private function initUser($uID) {
            $this->_userID = $uID;

            $data = $this->getUserDataSetByID();

            $this->_user_first_name = $data['user_first_name'];
            $this->_user_last_name = $data['user_last_name'];
            $this->_email = $data['email'];
            $this->_password = $data['password'];
            $this->_mainProject = $data['project_id'];

            $_SESSION['email'] = $this->_email;

        }

        public function createNewUserAccount($fname, $lname, $email, $pass) {
            global $database;

            $query = "INSERT INTO users
                      SET user_first_name = '" . $fname . "',
                          user_last_name = '" . $lname . "',
                          email = '" . $email . "',
                          password = '" . md5($pass) . "',
                          active = 1;";

            $result = $database->query($query);

            if ($database->affectedRows($result) == 1) {
                $uID = $database->getLastID();
                $this->initUser($uID);
                return TRUE;
            }
            else {
                return FALSE;
            }

        }

        public function registerAdmin() {
            global $database;

            $query = "INSERT INTO admin
                      SET user_id = '" . $this->_userID . "';";

            if ($this->_userID == "") {
                die("User ID blank");
            }
            $result = $database->query($query);

            if ($database->affectedRows($result) == 1) {
                return TRUE;
            }
            else {
                return FALSE;
            }

        }

        public function getUserDataSetByID() {
            global $database;

            $query = "SELECT *
                      FROM users
                      WHERE user_id = '" . $this->_userID . "';";

            $result = $database->query($query);


            if ($database->numRows($result) == 1) {
                $data = $database->fetchArray($result);
                return $data;
            }
            else {
                return FALSE;
            }

        }

        public function getUserResultSet() {
            global $database;

            $query = "SELECT *
                      FROM users;";

            $result = $database->query($query);

            return $result;

        }

        /**
         * Function gets user ID
         *
         * @param
         * @access public
         *
         * @return String User ID
         *
         */
        public function getUserID() {
            return $this->_userID;

        }

        /**
         * Function gets users email
         *
         * @param
         * @access public
         *
         * @return String User Email
         *
         */
        public function getUserEmail() {
            return $this->_email;

        }

        /**
         * Function gets users status
         *
         * @param
         * @access public
         *
         * @return Boolean TRUE if active, FALSE otherwise
         *
         */
        public function getIsUserActive() {
            return $this->_isActive;

        }

        /**
         * Function sets User ID
         *
         * @param String $id User ID
         * @access private
         *
         * @return
         *
         */
        private function setUserID($id) {
            $this->_userID = $id;

        }

        /**
         * Function sets user password
         *
         * @param String $password Password
         * @access private
         *
         * @return
         *
         */
        private function setPassword($password) {
            $this->_password = $password;

        }

        /**
         * Function sets user email
         *
         * @param String $email Email
         * @access private
         *
         * @return
         *
         */
        private function setUserEmail($email) {
            $this->_email = $email;

        }

        private function setIsActive($status) {
            $this->_isActive = $status;

        }

        /**
         * Function checks whether user is logged in
         * Sets Flag to TRUE if logged in, FALSE if not
         *
         * @param
         * @access private
         *
         * @return
         *
         */
        private function checkUserLogin() {
            if (isset($_SESSION['user_id'])) {
                $this->_userID = $_SESSION['user_id'];
                $this->_userIsLoggedIn = true;
            }
            else {
                unset($this->_userID);
                $this->_userIsLoggedIn = false;
            }

        }

        /**
         * Function checks whether user is logged in
         *
         * @param
         * @access public
         *
         * @return Boolean TRUE if logged in, FALSE if not
         */
        public function isUserLoggedIn() {
            return $this->_userIsLoggedIn;

        }

        /**
         * Function authenticates user login attempt
         *
         * @param String $email Email
         * @param String $password Password
         *
         * @access public
         * @static
         * @return String|Boolean Email if login Successful, FALSE otherwise
         *
         */
        public static function authenticateUser($email = '', $password = '') {
            global $database;

            $query = "SELECT user_id, active
			 FROM users
			 WHERE email = '$email'
			 AND password = '" . md5($password) . "'
			 LIMIT 1;";

            echo "<br />AUTH Query: " . $query . "<br />";

            $result = $database->query($query);
            $data = $database->fetchArray($result);

            if (!empty($data)) {
                return $data['user_id'];
            }
            else {
                return FALSE;
            }

        }

        public function userEmailExists($newEmail) {
            global $database;

            $query = "SELECT *
                      FROM users
                      WHERE email = '" . $newEmail . "';";

            $result = $database->query($query);

            if ($database->numRows($result) == 1) {
                return TRUE;
            }
            else {
                return FALSE;
            }

        }

        /**
         * Function logs user in
         *
         * @param String $user_id
         * @access public
         *
         * @return
         *
         */
        public function loginUser($user_id) {
            if ($user_id) {
                $this->_userID = $_SESSION['user_id'] = $user_id;
                $this->_userIsLoggedIn = TRUE;
                $this->initUser($this->_userID);
            }

        }

        /**
         * Function logs user out
         *
         * @param
         * @access public
         *
         * @return
         *
         */
        public function logoutUser() {
            unset($_SESSION['user_id']);
            //unset($_SESSION['email']);
            unset($this->_UserID);
            $this->_userIsLoggedIn = false;

            // re-generate session ID
            session_regenerate_id(true);

        }

        /**
         * Function generates a new password
         *
         * @param
         * @access public
         *
         * @return String Password
         *
         */
        public function generatePassword() {
            $pass = "";
            $c = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ)(-_!@=.0123456789";
            /*             * for($i=0;$i<26;$i++)
              $c[$i]=chr(97+$i);
              for($i=0;$i<26;$i++)
              $c[$i+26]=chr(65+$i);
              $c[52]=")";
              $c[53]="(";
              $c[54]="-";
              $c[55]="_";
              $c[56]="!";
              $c[57]="@";
              $c[58]="=";
              $c[59]=".";
              for($i=0;$i<10;$i++)
              $c[$i+60]=$i;* */
            $pass = "";
            for ($i = 0; $i < 12; $i++)
                $pass.=$c[rand(0, 69)];
            return $pass;

        }

        /**
         * Function validates email address input
         *
         * @param String $email Email
         * @access public
         *
         * @return Boolean 1 if matches, otherwise 0
         *
         */
        public function emailCheck($email) {
            $pattern = '/^[^\W][a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\.[a-zA-Z]{2,4}$/';

            preg_match($pattern, $email, $matches);

            if (!empty($matches)) {
                return 1;
            }
            else
                return 0;

        }

        /**
         * Function is a wrapper for filer_var and
         * validates entered email
         *
         * @param String $address
         * @access public
         *
         * @return Boolean TRUE if successful, otherwise FALSE
         *
         */
        public function updateProfile($realname, $new_pass) {
            global $database;

            $query = "UPDATE users
                      SET password = '$new_pass',
			  realname = '$realname'
		      WHERE uid = '" . $this->_userID . "';";

            $result = $database->query($query);

            if ($database->affectedRows($result) == 1) {
                return TRUE;
            }
            else {
                return FALSE;
            }

        }

        /**
         * Function updates user password
         *
         * @param String $new_pass New Password
         * @access public
         *
         * @return Boolean TRUE if Successful, FALSE otherwise
         *
         */
        public function updateUserPassword($new_pass) {
            global $database;

            $query = "UPDATE users
                      SET password = '$new_pass'
		      WHERE uid = '" . $this->_userID . "';";

            $result = $database->query($query);

            if ($database->affectedRows($result) == 1) {
                return TRUE;
            }
            else {
                return FALSE;
            }

        }

        /**
         * Function updates users realname
         *
         * @param String $realname Real Name
         * @access public
         *
         * @return Boolean TRUE if Successful, FALSE otherwise
         *
         */
        public function verifyPassword($password) {

            if ($password == $this->_password) {
                return TRUE;
            }
            else {
                return FALSE;
            }

        }

        /**
         * Function updates user ID in session table
         *
         * @param
         * @access public
         *
         * @return Boolean TRUE if session exists, otherwise FALSE
         *
         */
        public function updateSession() {
            global $database;
            global $session;

            $query = "UPDATE sessions
                      SET user_id = '" . $this->_userID . "'
                      WHERE session_id = '" . $session->getSessionID() . "';";

            //echo "Update Session Query: " . $query;
            $result = $database->query($query);

            if ($database->affectedRows($result) == 1) {
                return TRUE;
            }
            else {
                return FALSE;
            }

        }

        public function validatePassword($pass1, $pass2) {
            if (($pass1 == $pass2) && (strlen($pass1) >= 6)) {
                return TRUE;
            }
            else {
                return FALSE;
            }

        }

        public function addUserToProject($pID) {
            global $database;
            global $user;

            $query = "INSERT INTO userprojects (user_id, project_id)
                      VALUES ('" . $user->getUserID() . "', '" . $pID . "');";

            $result = $database->query($query);

            if ($database->affectedRows($result) == 1) {
                return TRUE;
            }
            else {
                return FALSE;
            }

        }

        public function getUserProjects($uID) {
            global $database;

            $query = "SELECT *
                      FROM userprojects
                      WHERE user_id = '" . $uID . "';";

            $result = $database->query($query);
            $data = $database->fetchArray($result);

            if (!empty($data)) {
                return $data;
            }
            else {
                return FALSE;
            }

        }

    }

    // When user first enters the site they are given a user_id
    // of 0... if they register, or login they get their real
    // user ID
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    }
    else {
        $user_id = 0;
    }

    $user = new UnitCheckUser($user_id);

?>
