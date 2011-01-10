<?php

    /**
     * Footer class is a template for Footer objects.
     *
     * Copyright 	(c) 2010, 2011 Tom Kaczocha <freedomdeveloper@yahoo.com>
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
     *
     * @package
     * @author	    Tom Kaczocha <freedomdeveloper@yahoo.com>
     * @copyright   2010, 2011 Tom Kaczocha
     * @version 	1.0
     * @access	    public
     * @License     "GNU General Public License", version="3.0"
     *
     */
    class UnitCheckFooter {

        /**
         * UnitCheckFooter Constructor
         *
         * @param
         * @access public
         *
         * @return
         *
         */
        public function __construct() {
            
        }

        /**
         * UnitCheckFooter Destructor
         *
         * @param
         * @access public
         *
         * @return
         *
         */
        public function __destruct() {
            
        }

        /**
         * Function prints UnitCheck footer
         *
         * @param
         * @access public
         *
         * @return
         *
         */
        public static function printFooter() {

            echo '</div> <!-- END unitcheck-body -->
                  <div id="footer">
                    <div class="intro"></div>
                    <ul id="useful-links">
                        <li id="links-actions">
                            <ul class="links">
                                <li>
                                    <a href="../public/index.php">Home</a>
                                </li>
                                <li>
                                    <span class="separator">| </span>
                                    <a href="../public/configure.php">Configure</a>
                                </li>
                                <li>
                                    <span class="separator">| </span>
                                    Copyright &copy; 2010, 2011 Tom Kaczocha
                                </li>
                                <li>
                                    <span class="separator">| </span>
                                    <a href="mailto:freedomdeveloper@yahoo.com?subject=UnitCheck Project Bug" title="Bug Reporting">Report Bugs</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <div class="outro"></div>
                </div> <!-- END footer -->
            </body>
        </html>';

        }

    }

