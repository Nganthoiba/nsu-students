<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use App\CustomLibrary\JWSExtractor;

class Student extends Model
{
    protected $table = "students";
    protected $connection = 'mongodb'; // Use MongoDB connection
    //protected $collection = 'students'; // Ensure this matches your MongoDB collection
    protected $guarded = []; // Allow mass assignment of any field (since fields are dynamic)
    
    
    // Method to check if a student information has been approved and esigned or not
    public function isSigned(): bool{
        if($this->approved == 0){
            return false;
        }
        if(!isset($this->approved_by['jws'])){
            return false;
        }
        return true;
    }
  
    // Method to check if the signature is valid or not
    public function isSignatureValid():bool
    {
        if(!isset($this->approved_by['jws'])){
            return false;
        }
        $jws = $this->approved_by['jws'];

        // Here is the logic to validate the JWS signature
        return JWSExtractor::isValidJWS($jws);
    }

    //Method to verify the integrity of the signed data with the JWS
    public function verifySignedData():bool
    {
        if(!isset($this->approved_by['jws'])){
            return false;
        }
        $jws = $this->approved_by['jws'];

        //Data to verify against the JWS
        $data = $this->toArray();
        // Exclude fields that are not part of the signed data
        unset($data['approved_by']); // Exclude the 'approved_by' from the data to verify
        unset($data['updated_at']); // Exclude 'updated_at' if it exists, as it may change over time
        unset($data['approved']); // Exclude 'approved' if it exists, as it may change over time

        // Here is the logic to verify the integrity of the signed data
        return JWSExtractor::verifySignedData($jws, $data);
    }

    // Method to get the Signer name
    public function getSignerName():string
    {
        // Check if the student information is signed
        if(!$this->isSigned()){
            return 'Not signed yet';
        }
        // If the document is not signed or JWS is not set, return a default message
        if(!isset($this->approved_by['jws'])){
            return 'No Signature Found';
        }

        $jws = $this->approved_by['jws'];
        return JWSExtractor::getSignerName($jws);
    }

    // Method to get the jws
    public function getJWS():?string
    {
        // Check if the student information is signed
        if(!$this->isSigned()){
            return null;
        }
        // If the document is not signed or JWS is not set, return null
        if(!isset($this->approved_by['jws'])){
            return null;
        }

        return $this->approved_by['jws'];
    }

    public function isSameAsSignedData($key){
        $payload = JWSExtractor::decodePayload($this->approved_by['jws']);
        if(!isset($payload[$key])){
            return false;
        }
        return(trim($this->{$key}) === trim($payload[$key]));        
    }

    public function getSignedData($key){
        $payload = JWSExtractor::decodePayload($this->approved_by['jws']);
        if(!isset($payload[$key])){
            return null;
        }
        return $payload[$key];
    }

    public function getAllSignedData(){
        if(!$this->isSigned()){
            return null;
        }
        $data = JWSExtractor::decodePayload($this->approved_by['jws']);
        return $data;
    }

    public function getRawData(){
        $data = $this->toArray();
        // Exclude fields that are not part of the signed data
        unset($data['approved_by']); // Exclude the 'approved_by' from the data
        unset($data['updated_at']); // Exclude 'updated_at' if it exists, as it may change over time
        unset($data['approved']); // Exclude 'approved' if it exists, as it may change over time
        return $data;
    }
}
