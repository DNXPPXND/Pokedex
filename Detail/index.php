<?php
function fetchData($url) {
    $response = @file_get_contents($url);
    return $response ? json_decode($response, true) : null;
}

$typeColors = [
    'fire' => 'bg-orange-500', 'water' => 'bg-blue-500', 'grass' => 'bg-green-500',
    'electric' => 'bg-yellow-400', 'ice' => 'bg-cyan-300', 'fighting' => 'bg-red-700',
    'poison' => 'bg-purple-500', 'ground' => 'bg-yellow-700', 'flying' => 'bg-indigo-300',
    'psychic' => 'bg-pink-500', 'bug' => 'bg-lime-500', 'rock' => 'bg-stone-600',
    'ghost' => 'bg-violet-800', 'dragon' => 'bg-indigo-700', 'dark' => 'bg-gray-800',
    'steel' => 'bg-slate-400', 'fairy' => 'bg-pink-300', 'normal' => 'bg-gray-400'
];

$typeData = fetchData("https://pokeapi.co/api/v2/type");
$types = $typeData['results'] ?? [];
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokédex Mainframe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Press+Start+2P&family=VT323&display=swap');
        
        body { background-color: #2d3436; font-family: 'VT323', monospace; }
        .retro-font { font-family: 'Press Start 2P', cursive; }

        /* ตัวเครื่อง Pokedex */
        .pokedex-case {
            background-color: #dc0a2d;
            border: 8px solid #8b0000;
            border-radius: 20px;
            box-shadow: 15px 15px 0px #5e0000;
        }

        /* หน้าจอ LCD */
        .pokedex-screen {
            background-color: #1a1a1a;
            border: 12px solid #404040;
            border-radius: 10px;
            box-shadow: inset 0 0 20px rgba(0,0,0,0.9);
            height: 70vh;
        }

        /* การ์ดในหน้าจอ */
        .pokemon-card {
            background: #2a2a2a;
            border: 2px solid #444;
            image-rendering: pixelated;
        }
        .pokemon-card:hover {
            border-color: #eee;
            background: #333;
        }

        /* Dropdown Custom */
        .custom-dropdown:hover .dropdown-content { display: block; }
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: #404040;
            border: 4px solid #222;
            min-width: 150px;
            z-index: 50;
            max-height: 250px;
            overflow-y: auto;
        }

        ::-webkit-scrollbar { width: 10px; }
        ::-webkit-scrollbar-track { background: #1a1a1a; }
        ::-webkit-scrollbar-thumb { background: #dc0a2d; border: 2px solid #404040; }

        .status-light-big {
            width: 45px; height: 45px;
            background: radial-gradient(circle at 30% 30%, #60a5fa, #1d4ed8);
            border: 4px solid white;
            box-shadow: 0 0 15px rgba(255,255,255,0.5);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-2 md:p-6">

    <div class="max-w-6xl w-full pokedex-case overflow-hidden">
        
        <div class="p-4 md:p-6 border-b-8 border-red-800 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="status-light-big rounded-full animate-pulse"></div>
                <div class="hidden sm:flex gap-2">
                    <div class="w-4 h-4 rounded-full bg-red-600 border-2 border-black"></div>
                    <div class="w-4 h-4 rounded-full bg-yellow-400 border-2 border-black"></div>
                    <div class="w-4 h-4 rounded-full bg-green-500 border-2 border-black"></div>
                </div>
                <h1 class="text-white retro-font text-sm md:text-lg ml-2 italic tracking-tighter">POKÉDEX</h1>
            </div>

            <div class="flex items-center gap-3 w-full md:w-auto">
                <input type="text" id="pokemonSearch" placeholder="SEARCH..." 
                       class="flex-grow md:w-64 bg-black text-green-500 border-4 border-gray-600 px-4 py-1 font-mono text-sm focus:outline-none focus:border-blue-400">
                
                <div class="relative custom-dropdown">
                    <button class="bg-gray-800 text-white px-4 py-2 border-b-4 border-black font-bold text-xs uppercase hover:bg-gray-700">
                        TYPE ▾
                    </button>
                    <div class="dropdown-content">
                        <?php foreach($types as $t): 
                            if($t['name'] == 'shadow' || $t['name'] == 'unknown') continue; ?>
                            <a href="#type-<?php echo $t['name']; ?>" class="block px-4 py-2 text-white text-[10px] hover:bg-red-600 border-b border-black uppercase">
                                <?php echo $t['name']; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="pokedex-screen m-4 p-4 md:p-6 overflow-y-auto">
            
            <?php foreach ($types as $type): 
                $typeName = $type['name'];
                if ($typeName === 'shadow' || $typeName === 'unknown') continue;
                
                $bgColor = $typeColors[$typeName] ?? 'bg-gray-500';
                $detail = fetchData($type['url']);
                $pokemonList = $detail['pokemon'] ?? [];
            ?>
                <section id="type-<?php echo $typeName; ?>" class="type-section mb-12">
                    <div class="sticky top-0 z-20 py-1 bg-[#1a1a1a] flex items-center mb-4 border-b-2 border-gray-700">
                        <span class="w-3 h-3 bg-red-600 rounded-full mr-3"></span>
                        <h2 class="text-white retro-font text-[10px] uppercase">
                            <?php echo $typeName; ?> <span class="text-gray-500">[<?php echo count($pokemonList); ?>]</span>
                        </h2>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 gap-4">
                        <?php foreach ($pokemonList as $p): 
                            $name = $p['pokemon']['name'];
                            $urlParts = explode('/', rtrim($p['pokemon']['url'], '/'));
                            $id = end($urlParts);
                            if ($id > 1025 && $id < 10000) continue; 
                        ?>
                            <a href="detail.php?id=<?php echo $id; ?>" 
                               data-name="<?php echo strtolower($name); ?>"
                               class="pokemon-card p-3 rounded flex flex-col items-center group transition-all active:translate-y-1">
                                
                                <div class="w-full bg-white rounded p-2 mb-2 shadow-inner border-2 border-black">
                                    <img src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/<?php echo $id; ?>.png" 
                                         loading="lazy" alt="pkmn" 
                                         class="w-full h-auto group-hover:scale-110 transition-transform">
                                </div>
                                
                                <p class="text-[10px] text-gray-500 mb-1">ID: <?php echo str_pad($id, 3, '0', STR_PAD_LEFT); ?></p>
                                <h3 class="text-white text-xs font-bold capitalize truncate w-full text-center mb-2">
                                    <?php echo $name; ?>
                                </h3>
                                
                                <div class="w-full h-1.5 <?php echo $bgColor; ?> rounded-full border border-black/50"></div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endforeach; ?>

            <div id="noResults" class="hidden text-center py-20">
                <p class="text-red-500 retro-font text-xs">NO DATA FOUND</p>
            </div>
        </div>

        <div class="p-4 bg-red-700 flex justify-between items-center px-10 border-t-4 border-red-900">
            <div class="flex gap-4">
                <div class="w-16 h-3 bg-blue-900 rounded-full border-2 border-black"></div>
                <div class="w-16 h-3 bg-green-900 rounded-full border-2 border-black"></div>
            </div>
            <div class="w-12 h-12 bg-black rounded-full border-4 border-gray-600"></div>
        </div>
    </div>

    <script>
        const searchInput = document.getElementById('pokemonSearch');
        const cards = document.querySelectorAll('.pokemon-card');
        const sections = document.querySelectorAll('.type-section');
        const noResults = document.getElementById('noResults');

        searchInput.addEventListener('input', (e) => {
            const query = e.target.value.toLowerCase();
            let hasResults = false;

            cards.forEach(card => {
                const name = card.getAttribute('data-name');
                if (name.includes(query)) {
                    card.style.display = 'flex';
                    hasResults = true;
                } else {
                    card.style.display = 'none';
                }
            });

            sections.forEach(section => {
                const visibleCards = section.querySelectorAll('.pokemon-card[style="display: flex;"]');
                if (visibleCards.length === 0 && query !== "") {
                    section.style.display = 'none';
                } else {
                    section.style.display = 'block';
                }
            });

            noResults.style.display = hasResults ? 'none' : 'block';
        });
    </script>
</body>
</html>
