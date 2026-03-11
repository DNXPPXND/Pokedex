<?php
$id = $_GET['id'] ?? 1;
$pokemon = json_decode(@file_get_contents("https://pokeapi.co/api/v2/pokemon/$id"), true);
$species = json_decode(@file_get_contents($pokemon['species']['url']), true);

if (!$pokemon) die("Specimen Not Found");

$mainType = $pokemon['types'][0]['type']['name'];
$description = "";
foreach ($species['flavor_text_entries'] as $entry) {
    if ($entry['language']['name'] == 'en') {
        $description = str_replace(["\n", "\f"], " ", $entry['flavor_text']);
        break;
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokédex - <?php echo ucfirst($pokemon['name']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Press+Start+2P&family=VT323&display=swap');
        
        body { background-color: #2d3436; font-family: 'VT323', monospace; }
        .retro-font { font-family: 'Press Start 2P', cursive; }
        
        /* Pokédex Main Body */
        .pokedex-case {
            background-color: #dc0a2d;
            border: 8px solid #8b0000;
            border-radius: 20px 20px 20px 100px;
            box-shadow: 20px 20px 0px #5e0000;
        }

        /* LCD Screen */
        .lcd-screen {
            background-color: #98cb98; /* Classic GameBoy Green-ish */
            border: 4px solid #404040;
            box-shadow: inset 4px 4px 10px rgba(0,0,0,0.5);
            color: #1a1a1a;
        }

        /* Status Light */
        .status-light {
            width: 50px; height: 50px;
            background: radial-gradient(circle at 30% 30%, #60a5fa, #1d4ed8);
            border: 4px solid white;
            box-shadow: 0 0 15px rgba(255,255,255,0.8);
        }

        /* D-Pad Center */
        .d-pad {
            width: 80px; height: 80px;
            background: #222;
            position: relative;
            clip-path: polygon(35% 0%, 65% 0%, 65% 35%, 100% 35%, 100% 65%, 65% 65%, 65% 100%, 35% 100%, 35% 65%, 0% 65%, 0% 35%, 35% 35%);
        }

        .stat-bar-container { background: #333; height: 10px; border: 1px solid #000; }
        .stat-bar-fill { height: 100%; background: #2ecc71; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    <div class="max-w-4xl w-full pokedex-case p-6 md:p-10 relative overflow-hidden">
        
        <div class="flex items-start gap-4 mb-8">
            <div class="status-light rounded-full animate-pulse"></div>
            <div class="flex gap-2">
                <div class="w-4 h-4 rounded-full bg-red-600 border-2 border-black shadow"></div>
                <div class="w-4 h-4 rounded-full bg-yellow-400 border-2 border-black shadow"></div>
                <div class="w-4 h-4 rounded-full bg-green-500 border-2 border-black shadow"></div>
            </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-10">
            
            <div class="space-y-6">
                <div class="bg-gray-200 p-6 rounded-lg border-[12px] border-gray-400 shadow-inner flex flex-col items-center">
                    <div class="w-full bg-white rounded border-4 border-gray-800 p-4 shadow-inner relative">
                        <span class="absolute top-1 right-2 text-[10px] font-bold text-gray-400">#<?php echo str_pad($id, 3, '0', STR_PAD_LEFT); ?></span>
                        <img src="<?php echo $pokemon['sprites']['other']['official-artwork']['front_default']; ?>" 
                             class="w-full h-auto drop-shadow-lg" alt="pokemon">
                    </div>
                    <div class="w-full mt-4 flex justify-between items-center px-2">
                        <div class="w-6 h-6 rounded-full bg-red-600 border-2 border-black"></div>
                        <div class="flex space-x-4">
                            <div class="w-8 h-1 bg-gray-600 rounded"></div>
                            <div class="w-8 h-1 bg-gray-600 rounded"></div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center px-4">
                    <div class="w-12 h-12 bg-black rounded-full shadow-lg border-2 border-gray-800"></div>
                    <div class="d-pad"></div>
                </div>
            </div>

            <div class="space-y-4">
                <div class="lcd-screen p-6 rounded-lg min-h-[300px] flex flex-col">
                    <h2 class="retro-font text-xs mb-4 uppercase underline"><?php echo $pokemon['name']; ?></h2>
                    
                    <div class="text-sm space-y-2 mb-4 overflow-y-auto max-h-32">
                        <p class="leading-tight"><?php echo $description ?: "No data recorded for this specimen in the database."; ?></p>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-xs font-bold mb-4 border-t-2 border-black/20 pt-2">
                        <p>HT: <?php echo $pokemon['height']/10; ?>m</p>
                        <p>WT: <?php echo $pokemon['weight']/10; ?>kg</p>
                    </div>

                    <div class="space-y-2 flex-grow">
                        <?php foreach(array_slice($pokemon['stats'], 0, 4) as $s): ?>
                            <div>
                                <div class="flex justify-between text-[10px] uppercase font-bold">
                                    <span><?php echo str_replace('special-', 's-', $s['stat']['name']); ?></span>
                                    <span><?php echo $s['base_stat']; ?></span>
                                </div>
                                <div class="stat-bar-container">
                                    <div class="stat-bar-fill" style="width: <?php echo ($s['base_stat']/150)*100; ?>%"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-2">
                    <a href="index.php" class="bg-blue-500 text-white text-center py-3 rounded-lg border-b-4 border-blue-800 active:border-0 active:translate-y-1 font-bold text-xs uppercase">Back</a>
                    <a href="detail.php?id=<?php echo $id-1; ?>" class="bg-green-800 text-white text-center py-3 rounded-lg border-b-4 border-black active:border-0 active:translate-y-1 font-bold text-xs uppercase <?php echo $id <= 1 ? 'opacity-50 pointer-events-none' : ''; ?>">Prev</a>
                    <a href="detail.php?id=<?php echo $id+1; ?>" class="bg-orange-800 text-white text-center py-3 rounded-lg border-b-4 border-black active:border-0 active:translate-y-1 font-bold text-xs uppercase">Next</a>
                </div>

            
            </div>

        </div>

        <div class="absolute bottom-6 right-10 flex flex-col gap-1 opacity-20">
            <div class="w-12 h-1 bg-black rounded"></div>
            <div class="w-12 h-1 bg-black rounded"></div>
            <div class="w-12 h-1 bg-black rounded"></div>
        </div>
    </div>

</body>
</html>
