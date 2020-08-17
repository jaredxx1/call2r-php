<?php


namespace App\Company\Application\Query;


use App\Core\Infrastructure\Container\Application\Utils\Query\QueryInterface;
use Webmozart\Assert\Assert;

/**
 * Class FindCompaniesBySectionIdQuery
 * @package App\Company\Application\Query
 */
class FindCompaniesBySectionIdQuery implements QueryInterface
{
    /**
     * @var int
     */
    private $sectionId;

    /**
     * FindCompaniesBySectionIdQuery constructor.
     * @param int $sectionId
     */
    public function __construct(int $sectionId)
    {
        $this->sectionId = $sectionId;
    }

    /**
     * @param array $data
     * @return FindCompaniesBySectionIdQuery
     */
    public static function fromArray($data)
    {
        Assert::keyExists($data, 'sectionId', "Id must be a integer");
        Assert::integer($data['sectionId'], "Id must be a integer");

        return new self(
            $data['sectionId']
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
     * @return int
     */
    public function getSectionId(): int
    {
        return $this->sectionId;
    }
}