<?php
require_once './../DbConnection.php';
class Driver{
    private $conn;
    private $id;
    private $image;
    private $first_name;
    private $middle_name;
    private $last_name;
    private $gender;
    
    private $NIC_number;
    private $email;
    private $mobile;
    private $address;
    private $district;

    public function __construct($id =null, $image=null,$first_name=null, $middle_name=null, $last_name=null, $birthday=null, $gender=null,  $NIC_number=null, $email=null, $mobile=null, $address=null, $district=null){
        $this->conn = (new DbConnector())->getConnection();
        
        $this->image = $image;$this->id = $id;
        $this->first_name = $first_name;
        $this->middle_name = $middle_name;
        $this->last_name = $last_name;
        $this->gender = $gender;
        $this->NIC_number = $NIC_number;
        $this->email = $email;
        $this->mobile = $mobile;
        $this->address = $address;
        $this->district = $district;
    }

    public function createDriver(){
        $sql = "INSERT INTO driver_profile (image,first_name, middle_name, last_name,birthday,gender,  NIC_number, email,mobile,address, district) VALUES (:first_name, :middle_name, :last_name, :gender, :image, :NIC_number, :email, :mobile, :address.:district)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['image'=>$this->image,'first_name'=>$this->first_name, 'middle_name'=>$this->middle_name, 'last_name'=>$this->last_name, 'gender'=>$this->gender,  'NIC_number'=>$this->NIC_number,'email'=>$this->email, 'mobile'=>$this->mobile, 'address'=>$this->address,'district'=>$this->district]);
        return $stmt->rowCount();

    }
    
    // READ FROM ID
    public function getDriver(){
        $sql = "SELECT * FROM driver WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id'=>$this->id]);
        $result = $stmt->fetch();

        if ($result) {
            foreach ($result as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }
        }
        return $result;
    }
    // UPDATE FROM ID
    public function updateDriver(){
        $sql = "UPDATE driver SET image = :image,first_name = :first_name, middle_name = :middle_name, last_name = :last_name,gender=:gender,  NIC_number = :NIC_number, email = :email, mobile = :mobile, address = :address , district = district WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id'=>$this->id,'image'=>$this->image, 'first_name'=>$this->first_name, 'middle_name'=>$this->middle_name, 'last_name'=>$this->last_name, 'birthday'=>$this->birthday, 'gender'=>$this->gender,  'NIC_number'=>$this->NIC_number,  'email'=>$this->email, 'mobile'=>$this->mobile, 'address'=>$this->address, 'district'=>$this->district]);
        
        if ($stmt->rowCount()) {
            return true;
        } {
            return false;
        }
       
    }

    public function getId(){
        return $this->id;
    }    
    public function getImage(){
        return $this->image;
    }
    public function getfirstName(){
        return $this->first_name;
    }
    public function getmiddleName(){
        return $this->middle_name;
    }
    public function getlastName(){
        return $this->last_name;
    }
    public function getFullname(){
        return $this->first_name." ".$this->middle_name." ".$this->last_name;
    }
    public function getBirthday(){
        return $this->birthday;
    }
    public function getGender(){
        return $this ->gender;
    }

    public function getNIC_number(){
        return $this->NIC_number;
    }
    public function getEmail(){
        return $this->email;
    }
    public function getMobile(){
        return $this->mobile;
    }
    public function getAddress(){
        return $this->address;
    }
    public function getDistrict(){
        return $this->district;
    }
    
     

}
?>