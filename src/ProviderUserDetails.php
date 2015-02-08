<?php namespace AdamWathan\EloquentOAuth;

/**
 * @property-read string $accessToken
 * @property-read string $userId
 * @property-read string $nickname
 * @property-read string $firstName
 * @property-read string $lastName
 * @property-read string $email
 * @property-read string $imageUrl
 */
class ProviderUserDetails
{
    protected $details = array(
        'accessToken' => null,
        'userId' => null,
        'nickname' => null,
        'firstName' => null,
        'lastName' => null,
        'email' => null,
        'imageUrl' => null,
    );

    protected $raw = array();

    public function __construct($details, $raw = array())
    {
        $this->addDetails($details);
        $this->raw =  $raw;
    }

    protected function addDetails($details = array())
    {
        foreach ($details as $key => $value) {
            $this->details[$key] = $value;
        }
    }

    public function raw()
    {
        return $this->raw;
    }

    public function __get($key)
    {
        return isset($this->details[$key]) ? $this->details[$key] : null;
    }
}
