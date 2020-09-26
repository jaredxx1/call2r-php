<?php


namespace App\Attendance\Application\Query;


use App\Attendance\Application\Exception\InitialDateIsGreaterThenFinalException;
use App\Core\Infrastructure\Container\Application\Exception\InvalidDateFormatException;
use App\Core\Infrastructure\Container\Application\Utils\Command\CommandInterface;
use Carbon\Carbon;
use Exception;
use Webmozart\Assert\Assert;

/**
 * Class ExportRequestsToPdfQuery
 * @package App\Attendance\Application\Query
 */
class ExportRequestsToPdfQuery implements CommandInterface
{
    /**
     * @var string|null
     */
    private $title;

    /**
     * @var string|null
     */
    private $initialDate;

    /**
     * @var string|null
     */
    private $finalDate;

    /**
     * @var integer|null
     */
    private $statusId;

    /**
     * @var integer|null
     */
    private $assignedTo;

    /**
     * @var integer|null
     */
    private $requestedBy;

    /**
     * ExportRequestsToPdfQuery constructor.
     * @param string|null $title
     * @param string|null $initialDate
     * @param string|null $finalDate
     * @param int|null $statusId
     * @param int|null $assignedTo
     * @param int|null $requestedBy
     */
    public function __construct(?string $title, ?string $initialDate, ?string $finalDate, ?int $statusId, ?int $assignedTo, ?int $requestedBy)
    {
        $this->title = $title;
        $this->initialDate = $initialDate;
        $this->finalDate = $finalDate;
        $this->statusId = $statusId;
        $this->assignedTo = $assignedTo;
        $this->requestedBy = $requestedBy;
    }


    /**
     * @param array $data
     * @return ExportRequestsToPdfQuery
     * @throws InitialDateIsGreaterThenFinalException
     * @throws InvalidDateFormatException
     */
    public static function fromArray($data)
    {
        if (key_exists('title', $data)) {
            Assert::stringNotEmpty($data['title'], 'Param title cannot be empty');
        }

        if (key_exists('initialDate', $data) && key_exists('finalDate', $data)) {
            try {
                $initialDate = Carbon::createFromFormat('Y-m-d', $data['initialDate']);
                $finalDate = Carbon::createFromFormat('Y-m-d', $data['finalDate']);
            } catch (Exception $e) {
                throw new InvalidDateFormatException();
            }

            if ($initialDate->gt($finalDate)) {
                throw new InitialDateIsGreaterThenFinalException();
            }

            $data['initialDate'] = $initialDate;
            $data['finalDate'] = $finalDate;
        }

        if (key_exists('initialDate', $data) && !(key_exists('finalDate', $data))) {
            try {
                $initialDate = Carbon::createFromFormat('Y-m-d', $data['initialDate']);
                $finalDate = Carbon::now()->timezone('America/Sao_Paulo');
            } catch (Exception $e) {
                throw new InvalidDateFormatException();
            }

            if ($initialDate->gte($finalDate)) {
                throw new InitialDateIsGreaterThenFinalException();
            }

            $data['initialDate'] = $initialDate;
            $data['finalDate'] = $finalDate;
        }


        if (key_exists('statusId', $data)) {
            if (!preg_match('/^[1-9]\d*(\.\d+)?$/', $data['statusId'])) {
                Assert::integer($data['statusId'], 'Param statusId not an integer.');
            }
        }

        if (key_exists('assignedTo', $data)) {
            if (!preg_match('/^[1-9]\d*(\.\d+)?$/', $data['assignedTo'])) {
                Assert::integer($data['assignedTo'], 'Param assignedTo not an integer.');
            }
        }

        if (key_exists('requestedBy', $data)) {
            if (!preg_match('/^[1-9]\d*(\.\d+)?$/', $data['requestedBy'])) {
                Assert::integer($data['requestedBy'], 'Param requestedBy not an integer.');
            }
        }

        return new self(
            $data['title'] ?? null,
            $data['initialDate'] ?? null,
            $data['finalDate'] ?? null,
            $data['statusId'] ?? null,
            $data['assignedTo'] ?? null,
            $data['requestedBy'] ?? null
        );
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [];
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @return string|null
     */
    public function getInitialDate(): ?string
    {
        return $this->initialDate;
    }

    /**
     * @return string|null
     */
    public function getFinalDate(): ?string
    {
        return $this->finalDate;
    }

    /**
     * @return int|null
     */
    public function getStatusId(): ?int
    {
        return $this->statusId;
    }

    /**
     * @return int|null
     */
    public function getAssignedTo(): ?int
    {
        return $this->assignedTo;
    }

    /**
     * @return int|null
     */
    public function getRequestedBy(): ?int
    {
        return $this->requestedBy;
    }


}