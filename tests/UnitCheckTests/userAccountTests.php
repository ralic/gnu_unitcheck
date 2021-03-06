<?php

    /**
     * This is the new user account test file
     *
     * Copyright (C) 2011 Tom Kaczocha
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
    require_once('../includes/initialise.php');

    // test for the successful new user
    // account creation
    function createNewUserAccountTest() {
        global $database;
        global $unitCheck;
        global $user;
        global $helper;

        $test = new UnitCheckTest("TEST - New User Account Created");
        $unitCheck->addTest($test);

        $result = $database->createFullDatabase('tests');

        if ($result) {
            mysql_select_db('tests', $database->getConnection());

            $uID = $user->createNewUserAccount("Tom", "Kaczocha",
                            "freedomdeveloper@yahoo.com", "password");

            $data = $user->getUserDataSetByID($uID);

            $database->dropDatabase('tests');
        }
        else {
            $data['email'] = "";
        }

        $test->failUnless($data['email'] == "freedomdeveloper@yahoo.com",
                "Error: New User Account Creation Failed");

    }

    // test verifies Email function
    // for correct functionality
    function validateEmailTest() {
        global $unitCheck;
        global $user;

        $emailTest = 0;

        $test = new UnitCheckTest("TEST - Validate Email");
        $unitCheck->addTest($test);

        $email1 = "freedomdeveloper@yahoo.com";
        $email2 = "free@yahoo";
        $email3 = "@yahoo.com";

        $result = $user->emailCheck($email1);
        if ($result == TRUE) {
            $emailTest++;
        }

        $result = $user->emailCheck($email2);
        if ($result == TRUE) {
            $emailTest++;
        }

        $result = $user->emailCheck($email3);
        if ($result == TRUE) {
            $emailTest++;
        }

        $test->failUnless($emailTest == 1,
                "Error: Email Validation Failed");

    }

    // test verifies validate Password
    // function for correct functionality
    function validatePasswordTest() {
        global $unitCheck;
        global $user;

        $passTest = 0;

        $test = new UnitCheckTest("TEST - Validate Password");
        $unitCheck->addTest($test);

        $pass1 = "vision";
        $pass2 = "free";
        $pass3 = "freeee";
        $pass4 = "";

        $result = $user->validatePassword($pass1, $pass1); // valid
        if ($result == TRUE) {
            $passTest++;
        }

        $result = $user->validatePassword($pass1, $pass3); // invalid
        if ($result == TRUE) {
            $passTest++;
        }

        $result = $user->validatePassword($pass3, $pass4); // invalid
        if ($result == TRUE) {
            $passTest++;
        }

        $test->failUnless($passTest == 1,
                "Error: Password Validation Failed");

    }

    // test ensures no duplicate
    // email accounts are created
    function duplicateEmailTest() {
        global $database;
        global $unitCheck;
        global $user;

        $count = 0;
        $newEmail = "freedomdeveloper@yahoo.com";

        $test = new UnitCheckTest("TEST - Duplicate User Account Creation Prevented");
        $unitCheck->addTest($test);

        $result = $database->createFullDatabase('tests');

        if ($result) {
            mysql_select_db('tests', $database->getConnection());

            $uID = $user->createNewUserAccount("Tom", "Kaczocha",
                            "freedomdeveloper@yahoo.com", "password");

            if ($uID != "") {
                $result = $user->userEmailExists($newEmail);

                if ($result == 0) {
                    $user->createNewUserAccount("Tom", "Kaczocha",
                            "freedomdeveloper@yahoo.com", "password");

                    $resultSet = $user->getUserResultSet();

                    while ($data = $database->fetchArray($resultSet, MYSQL_ASSOC)) {
                        if ($data['email'] == $newEmail) {
                            $count++;
                        }
                    }
                }
                else {
                    $count = 1;
                }
            }

            $database->dropDatabase('tests');
        }

        $test->failUnless($count == 1,
                "Error: Duplicate User Account Created");

    }

    // test ensures that when the first user
    // is created after the database is created
    // that user becomes an admin
    function firstUserIsAdminTest() {
        global $database;
        global $unitCheck;
        global $user;

        $userID = 1;
        $testResult = "";

        $test = new UnitCheckTest("TEST - First User Is Admin");
        $unitCheck->addTest($test);

        $result = $database->createFullDatabase('tests');

        if ($result) {
            mysql_select_db('tests', $database->getConnection());

            $uID = $user->createNewUserAccount("Tom", "Kaczocha",
                            "freedomdeveloper@yahoo.com", "password");

            // register admin
            // shouldn't be testing this here
            $result = $user->registerAdmin($uID);
            if ($result) {
                $testResult = $user->isUserAdmin($userID);
            }

            $database->dropDatabase('tests');
        }

        $test->failUnless($testResult,
                "Error: No Admin Created");

    }

    // test ensures that users first name
    // is updated successfully
    function userFirstNameUpdatedTest() {
        global $database;
        global $unitCheck;
        global $user;

        $testResult = "";
        $userID = 1;
        $fName = "Joe";
        $lName = "Kaczocha";
        $email = "freedomdeveloper@yahoo.com";

        $test = new UnitCheckTest("TEST - User First Name Updated");
        $unitCheck->addTest($test);

        $result = $database->createFullDatabase('tests');

        if ($result) {
            mysql_select_db('tests', $database->getConnection());

            $uID = $user->createNewUserAccount("Tom", "Kaczocha",
                            "freedomdeveloper@yahoo.com", "password");

            if ($uID == 1) {

                $result = $user->updateProfile($fName, $lName, $email);

                $data = $user->getUserDataSetByID($userID);

                $testResult = $test->assertEquals($data['user_first_name'], $fName);
            }

            $database->dropDatabase('tests');
        }

        $test->failUnless($testResult,
                "Error: Failed to Update Users First Name");

    }

    // test ensures that users last name
    // is updated successfully
    function userLastNameUpdatedTest() {
        global $database;
        global $unitCheck;
        global $user;

        $testResult = "";
        $userID = 1;
        $fName = "Tom";
        $lName = "Jones";
        $email = "freedomdeveloper@yahoo.com";

        $test = new UnitCheckTest("TEST - User Last Name Updated");
        $unitCheck->addTest($test);

        $result = $database->createFullDatabase('tests');

        if ($result) {
            mysql_select_db('tests', $database->getConnection());

            $uID = $user->createNewUserAccount("Tom", "Kaczocha",
                            "freedomdeveloper@yahoo.com", "password");

            if ($uID == 1) {

                $result = $user->updateProfile($fName, $lName, $email);

                $data = $user->getUserDataSetByID($userID);

                $testResult = $test->assertEquals($data['user_last_name'], $lName);
            }

            $database->dropDatabase('tests');
        }

        $test->failUnless($testResult,
                "Error: Failed to Update Users Last Name");

    }

    // test ensures that users email
    // is updated successfully
    function userEmailUpdatedTest() {
        global $database;
        global $unitCheck;
        global $user;

        $testResult = "";
        $userID = 1;
        $fName = "Tom";
        $lName = "Kaczocha";
        $email = "tomjones@yahoo.com";

        $test = new UnitCheckTest("TEST - User Email Updated");
        $unitCheck->addTest($test);

        $result = $database->createFullDatabase('tests');

        if ($result) {
            mysql_select_db('tests', $database->getConnection());

            $uID = $user->createNewUserAccount("Tom", "Kaczocha",
                            "freedomdeveloper@yahoo.com", "password");

            if ($uID == 1) {
                $result = $user->updateProfile($fName, $lName, $email);

                $data = $user->getUserDataSetByID($userID);

                $testResult = $test->assertEquals($data['email'], $email);
            }
            $database->dropDatabase('tests');
        }


        $test->failUnless($testResult,
                "Error: Failed to Update Users Email");

    }

    // test ensures that users password
    // is updated successfully
    function userPasswordUpdatedTest() {
        global $database;
        global $unitCheck;
        global $user;

        $testResult = "";
        $userID = 1;
        $newPass = "freedom";

        $test = new UnitCheckTest("TEST - User Password Updated");
        $unitCheck->addTest($test);

        $result = $database->createFullDatabase('tests');

        if ($result) {
            mysql_select_db('tests', $database->getConnection());

            $uID = $user->createNewUserAccount("Tom", "Kaczocha",
                            "freedomdeveloper@yahoo.com", "password");

            if ($uID == 1) {
                $result = $user->updateUserPassword($newPass);

                $data = $user->getUserDataSetByID($userID);

                $testResult = $test->assertEquals($data['password'], md5($newPass));
            }
            $database->dropDatabase('tests');
        }

        $test->failUnless($testResult,
                "Error: Failed to Update Users Password");

    }

    // test for the successful user
    // login
    function userSuccessfullyLoggedInTest() {
        global $database;
        global $user;
        global $unitCheck;

        $test = new UnitCheckTest("TEST - User Login Successful");
        $unitCheck->addTest($test);

        $result = $database->createFullDatabase('tests');

        if ($result) {
            mysql_select_db('tests', $database->getConnection());

            $uID = $user->createNewUserAccount("Tom", "Kaczocha",
                            "freedomdeveloper@yahoo.com", "password");

            if ($uID == 1) {
                // get user id for test
                $userID = 1;

                $user->loginUser($userID);

                $testResult = $user->isUserLoggedIn();
            }

            $database->dropDatabase('tests');
        }

        $test->failUnless($testResult,
                "Error: User not Logged in");

    }

?>
