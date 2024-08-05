<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">Update Form
            </h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="u\profile.php" method="get" class="mt-2 mb-2">
        <div class="modal-body">
          <div class="mt-4 mb-4" >

          <label for="image" class="form-label mt-2"><strong>Upload Image</strong></label>
            <input type="file" class="form-control" id="image" name="image">
            
            <input type="hidden" name="id" value="<?= $driver->getId(); ?>">
            <label for="firstName" class="form-label mt-2"><strong>First Name <span class="text-danger">*</span></strong></label>   <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Enter First Name" required>
            
            <label for="middleName" class="form-label mt-2"><strong>Middle Name <span class="text-danger">*</span></strong></label>
            <input type="text" class="form-control" id="middleName" name="middleName" placeholder="Enter Middle Name" required>
            
            <label for="lastName" class="form-label mt-2"><strong>Last Name <span class="text-danger">*</span></strong></label>
            <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Enter Last Name" required>
          
            <label for="gender" class="form-label mt-2"><strong>Gender <span class="text-danger">*</span></strong></label><br>
            <input class="form-check-input" type="radio" name="gender" id="genderMale" value="male">
            <label class="form-check-label" for="genderMale">
              Male
            </label>
            
            <input class="form-check-input" type="radio" name="gender" id="genderFemale" value="female">
            <label class="form-check-label" for="genderFemale">
              Female
            </label>
            
            <input class="form-check-input" type="radio" name="gender" id="genderOther" value="other">
            <label class="form-check-label" for="genderOther">
              Other
            </label><br>

            <label for="email" class="form-label mt-2"><strong>Email <span class="text-danger">*</span></strong></label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" required>

            <label for="mobile" class="form-label mt-2"><strong>Mobile <span class="text-danger">*</span></strong></label>
            <input type="number" class="form-control" id="mobile" name="mobile" placeholder="Enter Mobile Number" required>

            <label for="address" class="form-label mt-2"><strong>Address <span class="text-danger">*</span></strong></label>
            <input type="text" class="form-control" id="address" name="address" placeholder="Enter Address" required> </div>

            <label for="district" class="form-label mt-2"><strong>District <span class="text-danger">*</span></strong></label>
            <input type="text" class="form-control" id="" name="district" placeholder="Enter Your District" required> </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </div>
    </form>
    </div>
  </div>