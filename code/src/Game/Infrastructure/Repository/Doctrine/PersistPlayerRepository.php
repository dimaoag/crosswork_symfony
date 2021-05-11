<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\Repository\Doctrine;

use App\Game\Domain\Dto\RegisteredPlayerDto;
use App\Game\Domain\Enum\Level;
use App\Game\Domain\Exception\PlayerNotFoundException;
use App\Game\Domain\Model\Player;
use App\Game\Domain\Model\PlayerId;
use App\Game\Domain\Repository\PersistPlayerRepositoryInterface;
use App\Game\Domain\Service\Encoder\PasswordEncoderInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class PersistPlayerRepository extends ServiceEntityRepository implements PersistPlayerRepositoryInterface
{
    private PasswordEncoderInterface $encoder;

    public function __construct(ManagerRegistry $registry, PasswordEncoderInterface $encoder)
    {
        parent::__construct($registry, Player::class);
        $this->encoder = $encoder;
    }

    public function createPlayer(RegisteredPlayerDto $playerDto): void
    {
        $player = new Player($playerDto->playerId());
        $player->changeNickname($playerDto->nickname());
        $player->changeLevel($playerDto->level());
        $player->changeRole($playerDto->role());
        $player->changePassword($this->encoder->encodePassword($playerDto->password(), null));

        $this->store($player);
    }

    public function levelUp(PlayerId $playerId): void
    {
        $player = $this->createQueryBuilder('u')
            ->andWhere('u.playerId = :id')
            ->setParameter('id', (string) $playerId)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$player instanceof Player) {
            throw new PlayerNotFoundException();
        }

        $player->changeLevel(new Level((int) $player->level()->getValue() + 1));

        $this->store($player);
    }

    private function store(Player $player): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($player);
        $entityManager->flush($player);
    }
}
