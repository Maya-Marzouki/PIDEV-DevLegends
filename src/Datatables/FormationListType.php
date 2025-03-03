namespace App\Datatables;

use App\Entity\Formation;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Component\Routing\RouterInterface;

class FormationTableType implements DataTableTypeInterface
{
    public function configure(DataTable $dataTable, array $options)
    {
        $dataTable
            ->add('titreFor', TextColumn::class, ['label' => 'Titre'])
            ->add('dateFor', DateTimeColumn::class, ['label' => 'Date'])
            ->add('lieuFor', TextColumn::class, ['label' => 'Lieu'])
            ->add('statutFor', TextColumn::class, ['label' => 'Statut'])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Formation::class,
            ])
            ->addOrderBy('dateFor', 'desc');
    }
}
