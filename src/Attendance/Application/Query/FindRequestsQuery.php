<?php


namespace App\Attendance\Application\Query;


use App\Core\Infrastructure\Container\Application\Utils\Query\QueryInterface;
use Webmozart\Assert\Assert;

class FindRequestsQuery implements QueryInterface
{

    /**
     * @var boolean|null
     */
    private $awaitingSupport;

    /**
     * @var boolean|null
     */
    private $inAttendance;

    /**
     * @var boolean|null
     */
    private $awaitingResponse;

    /**
     * @var boolean|null
     */
    private $canceled;

    /**
     * @var boolean|null
     */
    private $approved;

    /**
     * @var boolean|null
     */
    private $active;

    /**
     * FindRequestsQuery constructor.
     * @param bool|null $awaitingSupport
     * @param bool|null $inAttendance
     * @param bool|null $awaitingResponse
     * @param bool|null $canceled
     * @param bool|null $approved
     * @param bool|null $active
     */
    public function __construct(?bool $awaitingSupport, ?bool $inAttendance, ?bool $awaitingResponse, ?bool $canceled, ?bool $approved, ?bool $active)
    {
        $this->awaitingSupport = $awaitingSupport;
        $this->inAttendance = $inAttendance;
        $this->awaitingResponse = $awaitingResponse;
        $this->canceled = $canceled;
        $this->approved = $approved;
        $this->active = $active;
    }

    public static function fromArray($data)
    {
        if (key_exists('awaitingSupport', $data)) {
            if(!self::validateBoolean($data['awaitingSupport'])){
                Assert::boolean($data['awaitingSupport'], "awaitingSupport must be a boolean");
            }
            $data['awaitingSupport'] = self::transformToBoolean($data['awaitingSupport']);
        }

        if (key_exists('inAttendance', $data)) {
            if(!self::validateBoolean($data['inAttendance'])){
                Assert::boolean($data['inAttendance'], "inAttendance must be a boolean");
            }
            $data['inAttendance'] = self::transformToBoolean($data['inAttendance']);
        }

        if (key_exists('awaitingResponse', $data)) {
            if(!self::validateBoolean($data['awaitingResponse'])){
                Assert::boolean($data['awaitingResponse'], "awaitingResponse must be a boolean");
            }
            $data['awaitingResponse'] = self::transformToBoolean($data['awaitingResponse']);
        }

        if (key_exists('canceled', $data)) {
            if(!self::validateBoolean($data['canceled'])){
                Assert::boolean($data['canceled'], "canceled must be a boolean");
            }
            $data['canceled'] = self::transformToBoolean($data['canceled']);
        }

        if (key_exists('approved', $data)) {
            if(!self::validateBoolean($data['approved'])){
                Assert::boolean($data['approved'], "approved must be a boolean");
            }
            $data['approved'] = self::transformToBoolean($data['approved']);
        }

        if (key_exists('active', $data)) {
            if(!self::validateBoolean($data['active'])){
                Assert::boolean($data['active'], "active must be a boolean");
            }
            $data['active'] = self::transformToBoolean($data['active']);
        }

        return new self(
            $data['awaitingSupport'] ?? null,
            $data['inAttendance'] ?? null,
            $data['awaitingResponse'] ?? null,
            $data['canceled'] ?? null,
            $data['approved'] ?? null,
            $data['active'] ?? null
        );
    }

    /**
     * @param $param
     * @return bool
     */
    public static function validateBoolean($param): bool
    {
        if($param == 'false' || $param == '0'){
            return true;
        }

        if($param == 'true' || $param == '1'){
            return true;
        }

        return false;

    }

    /**
     * @param $param
     * @return bool
     */
    private static function transformToBoolean($param)
    {
        if($param == 'false' || $param == '0'){
            $param = null;
        }

        if($param == 'true' || $param == '1'){
            $param = true;
        }

        return $param;
    }

    public function toArray(): array
    {
        return [];
    }

    /**
     * @return bool|null
     */
    public function getAwaitingSupport(): ?bool
    {
        return $this->awaitingSupport;
    }

    /**
     * @return bool|null
     */
    public function getInAttendance(): ?bool
    {
        return $this->inAttendance;
    }

    /**
     * @return bool|null
     */
    public function getAwaitingResponse(): ?bool
    {
        return $this->awaitingResponse;
    }

    /**
     * @return bool|null
     */
    public function getCanceled(): ?bool
    {
        return $this->canceled;
    }

    /**
     * @return bool|null
     */
    public function getApproved(): ?bool
    {
        return $this->approved;
    }

    /**
     * @return bool|null
     */
    public function getActive(): ?bool
    {
        return $this->active;
    }
}