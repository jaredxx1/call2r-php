<?php


namespace App\Attendance\Application\Query;


use App\Attendance\Application\Exception\InitialDateIsGraterThenFinalException;
use App\Attendance\Application\Exception\InvalidDateFormatException;
use App\Core\Infrastructure\Container\Application\Utils\Command\CommandInterface;
use Carbon\Carbon;
use Exception;
use Webmozart\Assert\Assert;

/**
 * Class CreatePdfQuery
 * @package App\Attendance\Application\Query
 */
class CreatePdfQuery implements CommandInterface
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
     * @var string|null
     */
    private $status;

    /**
     * @var integer|null
     */
    private $assignedTo;

    /**
     * @var integer|null
     */
    private $requestedBy;

    /**
     * CreatePdfQuery constructor.
     * @param string|null $title
     * @param string|null $initialDate
     * @param string|null $finalDate
     * @param string|null $status
     * @param int|null $assignedTo
     * @param int|null $requestedBy
     */
    public function __construct(?string $title, ?string $initialDate, ?string $finalDate, ?string $status, ?int $assignedTo, ?int $requestedBy)
    {
        $this->title = $title;
        $this->initialDate = $initialDate;
        $this->finalDate = $finalDate;
        $this->status = $status;
        $this->assignedTo = $assignedTo;
        $this->requestedBy = $requestedBy;
    }

    /**
     * @param array $data
     * @return CreatePdfQuery
     * @throws InitialDateIsGraterThenFinalException
     * @throws InvalidDateFormatException
     */
    public static function fromArray($data)
    {
        if (key_exists('title', $data)) {
            Assert::stringNotEmpty($data['title'], 'Field title cannot be empty');
        }

        if (key_exists('initialDate', $data) && key_exists('finalDate', $data)) {
            try{
                $initialDate = Carbon::createFromFormat('Y-m-d', $data['initialDate']);
                $finalDate = Carbon::createFromFormat('Y-m-d', $data['finalDate']);
            }catch (Exception $e){
                throw new InvalidDateFormatException();
            }

            if($initialDate->gte($finalDate)){
                throw new InitialDateIsGraterThenFinalException();
            }

            $data['initialDate'] = $initialDate;
            $data['finalDate'] = $finalDate;
        }

        if (key_exists('status', $data)) {
            Assert::stringNotEmpty($data['status'], 'Field title cannot be empty');
            Assert::oneOf($data['status'],
                [
                    "Aguardando Resposta",
                    "Aguardando Suporte",
                    "Aprovado",
                    "Cancelado",
                    "Em Atendimento"
                ],
                'Field priority is neither Aguardando Resposta neither Aguardando Suporte neither Aprovado neither Cancelado or Em Atendimento');
        }

        if(key_exists('assignedTo', $data)){
            if(!preg_match('/^[1-9]\d*(\.\d+)?$/', $data['assignedTo'])){
                Assert::integer($data['assignedTo'], 'Field assignedTo not an integer.');
            }
        }

        if(key_exists('requestedBy', $data)){
            if(!preg_match('/^[1-9]\d*(\.\d+)?$/', $data['requestedBy'])){
                Assert::integer($data['requestedBy'], 'Field requestedBy not an integer.');
            }
        }

        return new self(
            $data['title'] ?? null,
            $data['initialDate'] ?? null,
            $data['finalDate'] ?? null,
            $data['status'] ?? null,
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
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
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