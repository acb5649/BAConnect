<div id="registerModal" class="w3-modal">
                <div class="w3-modal-content w3-animate-top w3-card-4">
                    <header class="w3-container w3-lime w3-center w3-padding-32">
                        <span onclick="document.getElementById('registerModal').style.display='none'" class="w3-button w3-lime w3-xlarge w3-display-topright">×</span>
                        <h2 class="w3-wide"><i class="w3-margin-right"></i>Register </h2>
                    </header>
                    <form class="w3-container">
                        <p>
                            <label>First name</label>
                        </p>
                        <input class="w3-input w3-border" type="text" maxlength="50" value="" name="firstName" id="firstName" />
                        <p>
                            <label>Last name</label>
                        </p>
                        <input class="w3-input w3-border" type="text" maxlength="50" value="" name="lastName" id="lastName" />
                        <p>
                            <label>Email</label>
                        </p>
                        <input class="w3-input w3-border" type="text" maxlength="50" value="" name="email" id="email" />
                        <p>
                            <label>Password (Must be longer than 12 characters and contains at least 1 digit)</label>
                        </p>
                        <input class="w3-input w3-border" type="password" maxlength="50" value="" name="pwd" id="pwd" />
                        <p>
                            <label>Gender</label>
                        </p>
                        <label>Male</label>
                        <input class="w3-radio w3-border" type="radio" name="gender" value="Male" checked="checked" />
                        <label>Female</label>
                        <input class="w3-radio w3-border" type="radio" name="gender" value="Female" />
                        <label>Nonbinary</label>
                        <input class="w3-radio w3-border" type="radio" name="gender" value="NB" />
                        <p>
                            <label>Country</label>
                        </p>
                        <select class="w3-select w3-border" name="country">
                            <?php print countryList(); ?>
                        </select>
                        <p>
                            <label>Phone number</label>
                        </p>
                        <input class="w3-input w3-border" type="tel" value="" name="PhoneNumber" />
                        <p>
                            <label>Status</label>
                        </p>
                        <label>Student</label>
                        <input class="w3-check w3-border" type="checkbox" name="student" value=1 />
                        <label>Working Professional</label>
                        <input class="w3-check w3-border" type="checkbox" name="Working Professional" value=1 />
                        <p>
                            <label>Education</label>
                        </p>
                        <input name="addEntry" class="w3-button w3-block w3-lime w3-padding-16 w3-section w3-right" type="button" value="Add degree" onclick="addField()" />
                        <fieldset id="education"></fieldset>
                        <p>
                            <label>Work History</label>
                        </p>
                        <input name="addWorkEntry" class="w3-button w3-block w3-lime w3-padding-16 w3-section w3-right" type="button" value="Add empoyment" onclick="addWork()" />
                        <fieldset id="work"></fieldset>
                        <button class="w3-button w3-block w3-lime w3-padding-16 w3-section w3-right" type="submit">Register
                            <i class="fa fa-check"></i>
                        </button>
                        <button class="w3-button w3-red w3-section" onclick="document.getElementById('registerModal').style.display='none'">Close
                            <i class="fa fa-remove"></i>
                        </button>
                    </form>
                </div>
            </div>
<script>
            // When the user clicks anywhere outside of the modal, close it
            var registerModal = document.getElementById('registerModal');
            window.onclick = function(event) {
                if (event.target == modal) {
                    registerModal.style.display = "none";
                }
            }
</script>