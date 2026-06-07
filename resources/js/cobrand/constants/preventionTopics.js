const ART = "/images/cobrand/prevention";

export const preventionTopics = [
    {
        slug: "poids-minimum",
        title: "Poids inférieur à 50 kg",
        illustration: `${ART}/poids.png`,
        body: `Pour garantir votre sécurité, un poids minimum de 50 kg est requis pour donner son sang. En dessous de ce seuil, le volume de sang prélevé pourrait représenter un risque pour votre organisme.`,
    },
    {
        slug: "vih-hepatite-syphilis",
        title: "VIH, hépatite B ou C, syphilis",
        illustration: `${ART}/VIH.png`,
        body: `Ces maladies sont transmissibles par le sang. Pour protéger les receveurs, toute personne ayant été testée positive à l'un de ces virus ne peut pas donner son sang. Dans certains cas spécifiques (hépatite B guérie ou syphilis traitée) un don de plasma peut être envisagé.

En cas de doute sur votre situation, contactez le CTS.`,
    },
    {
        slug: "cancer",
        title: "Cancer au cours de la vie",
        illustration: `${ART}/cancer.png`,
        body: `Par principe de précaution, un antécédent de cancer contre-indique généralement le don de sang. Certaines exceptions existent pour des cancers superficiels traités et en rémission complète.

En cas de doute sur votre situation, contactez le CTS.`,
    },
    {
        slug: "hemochromatose",
        title: "Hémochromatose génétique",
        illustration: `${ART}/Hémochromatose_génétique.png`,
        body: `L'hémochromatose est une maladie génétique qui provoque une accumulation excessive de fer dans l'organisme. Dans ce cas, le don de sang est possible mais uniquement sur prescription médicale valide, datant de moins d'un an et précisant le diagnostic et la fréquence des dons.`,
    },
    {
        slug: "diabete",
        title: "Diabète",
        illustration: `${ART}/diabetes.png`,
        body: `Le diabète traité à l'insuline constitue une contre-indication permanente au don de sang. Un diabète de type 2 traité uniquement par régime ou par médicaments oraux peut, selon les cas, être compatible avec le don.

En cas de doute sur votre situation, contactez le CTS.`,
    },
    {
        slug: "cardiovasculaire",
        title: "Maladies cardiovasculaires",
        illustration: `${ART}/heart.png`,
        body: `Certaines maladies du cœur ou des vaisseaux sanguins sont incompatibles avec le don. Si vous êtes suivi pour une pathologie cardiovasculaire, nous vous recommandons de prendre contact avec le CTS avant de vous inscrire à une collecte.`,
    },
    {
        slug: "sejour-royaume-uni",
        title: "Séjour prolongé au Royaume-Uni",
        illustration: `${ART}/voyage.png`,
        body: `Un séjour cumulé de plus d'un an au Royaume-Uni entre 1980 et 1996 constitue une contre-indication permanente. Cette mesure vise à prévenir tout risque lié à la maladie de Creutzfeldt-Jakob, transmissible par le sang.`,
    },
    {
        slug: "don-recent",
        title: "Don trop récent",
        illustration: `${ART}/don_recent.png`,
        body: `Votre organisme a besoin de temps pour reconstituer ses réserves après un don. Un délai minimum est requis entre deux dons. Il varie selon le type de don effectué. Si ce délai n'est pas respecté, votre éligibilité sera simplement reportée.`,
    },
    {
        slug: "fievre-sante",
        title: "Fièvre / pas en bonne santé",
        illustration: `${ART}/fievre_temperature.png`,
        body: `En cas de fièvre, d'infection ou de symptômes grippaux, le don doit être reporté. Votre organisme est mobilisé pour combattre l'infection. Donner dans cet état ne serait ni bon pour vous ni pour le receveur. Vous pourrez donner dès que vous vous sentirez pleinement rétabli.`,
    },
    {
        slug: "medicaments-vaccin",
        title: "Médicaments ou vaccin récents",
        illustration: `${ART}/medicament_vaccin.png`,
        body: `Certains médicaments et vaccins nécessitent un délai d'attente avant de pouvoir donner son sang. Ce délai varie selon le traitement concerné.

En cas de doute sur votre situation, contactez le CTS.`,
    },
    {
        slug: "traitement-dentaire",
        title: "Traitement dentaire récent",
        illustration: `${ART}/traitement_dentaire.png`,
        body: `Un détartrage ou un traitement dentaire peut provoquer de petites lésions par lesquelles des bactéries peuvent s'infiltrer dans le sang. Un délai d'attente est nécessaire pour s'assurer de l'absence de toute infection ou inflammation avant le don.`,
    },
    {
        slug: "plaie-operation",
        title: "Plaie ou opération récente",
        illustration: `${ART}/plaie_operaation.png`,
        body: `Une intervention chirurgicale ou une plaie récente nécessite un temps de cicatrisation et de récupération. Selon la nature de l'opération, le délai avant de pouvoir donner à nouveau peut varier.

En cas de doute sur votre situation, contactez le CTS.`,
    },
    {
        slug: "tatouage-piercing",
        title: "Tatouage ou piercing récent",
        illustration: `${ART}/tatouage_piercing.png`,
        body: `Un tatouage ou un piercing crée une effraction cutanée qui peut être une voie d'entrée pour certains agents infectieux. Un délai d'attente est requis pour s'assurer qu'aucune infection n'a été contractée lors de la procédure.`,
    },
    {
        slug: "grossesse-allaitement",
        title: "Grossesse ou allaitement",
        illustration: `${ART}/grossesse.png`,
        body: `La grossesse et l'allaitement mobilisent les ressources de votre organisme. Le don de sang est contre-indiqué pendant cette période et durant un certain délai après l'accouchement ou l'arrêt de l'allaitement, pour protéger votre santé et celle de votre bébé.`,
    },
    {
        slug: "relations-sexuelles",
        title: "Relations sexuelles à risque",
        illustration: `${ART}/relation_sexuelle.png`,
        body: `Certains comportements sexuels augmentent le risque de contracter des maladies infectieuses transmissibles par le sang. Un délai d'attente est alors nécessaire avant de pouvoir donner, afin de garantir la sécurité du receveur.`,
    },
    {
        slug: "drogues-injectables",
        title: "Drogues injectables",
        illustration: `${ART}/seringue.png`,
        body: `La consommation de drogues par injection constitue une contre-indication permanente au don de sang. Le partage de seringues ou de matériel d'injection expose à des virus qui se transmettent par le sang, comme le VIH ou les hépatites, et qui peuvent rester indétectables pendant un certain temps.

En cas de doute sur votre situation, contactez le CTS.`,
    },
    {
        slug: "gastro-coloscopie",
        title: "Gastroscopie ou coloscopie récente",
        illustration: `${ART}/gastroscopie.png`,
        body: `Ces examens endoscopiques peuvent fragiliser les muqueuses et créer un risque d'infection bactérienne. Un délai de quatre mois est requis après une gastroscopie ou une coloscopie avant de pouvoir donner son sang.`,
    },
    {
        slug: "voyage-recent",
        title: "Voyage récent",
        illustration: `${ART}/voyage.png`,
        body: `Selon votre destination de voyage, un délai d'attente peut être requis avant de donner votre sang. Certaines régions du monde présentent un risque accru de maladies infectieuses transmissibles par le sang, paludisme, dengue, Zika, entre autres.

Consultez le travelcheck du CTS pour connaître votre situation.`,
    },
    {
        slug: "transfusion-greffe",
        title: "Transfusion sanguine ou greffe reçue",
        illustration: `${ART}/transfusion_greffe.png`,
        body: `Avoir reçu une transfusion sanguine, une greffe d'organe ou de tissu nécessite un délai d'attente avant de pouvoir donner à son tour. Cette mesure vise à écarter tout risque de transmission d'agents infectieux au receveur.`,
    },
];
