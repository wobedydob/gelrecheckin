<?php declare(strict_types=1);

$barbarian = new DnD\CharacterClass(
    1, // level
    'Barbarian', // class name
    new DnD\Dice(12), // hitDice
    [
        new DnD\Proficiency('Armor', ['light armor', 'medium armor', 'shields']),
        new DnD\Proficiency('Weapons', ['simple weapons', 'martial weapons']),
        new DnD\Proficiency('Tools', ['none']),
        new DnD\Proficiency('Saving Throws', ['Strength', 'Constitution']),
        new DnD\Proficiency('Skills', ['Intimidation', 'Survival']),
    ], // proficiencies
    [], // equipment
    [], // features
    true
);

$bard = new DnD\CharacterClass(
    1, // Level 1
    'Bard', // name
    new DnD\Dice(8), // hitDice
    [
        new DnD\Proficiency('Armor', ['light armor']),
        new DnD\Proficiency('Weapons', ['simple weapons', 'hand crossbows', 'longswords', 'rapiers', 'shortswords']),
        new DnD\Proficiency('Tools', ['three musical instruments of your choice']),
        new DnD\Proficiency('Saving Throws', ['Dexterity', 'Charisma']),
        new DnD\Proficiency('Skills', ['Persuasion', 'Performance', 'Sleight of Hand']),
    ], // proficiencies
    [], // equipment,
    [], // features
    true
);


$hauntedOne = new DnD\Background('Haunted One', ['Investigation', 'Religion'], []);

$str = 16;
$dex = 12;
$con = 14;
$int = 18;
$wis = 16;
$cha = 10;

//$str = 10;
//$dex = 14;
//$con = 12;
//$int = 16;
//$wis = 16;
//$cha = 18;

$data = [
    'wuppo', // characterName
    'Wob Jelsma', // playerName
    [
        $barbarian
    ], // classes
    $hauntedOne, // background
    new DnD\Race('Human', [], 30, []), // race
    [
        new DnD\AbilityScore('Strength', $str),
        new DnD\AbilityScore('Dexterity', $dex),
        new DnD\AbilityScore('Constitution', $con),
        new DnD\AbilityScore('Intelligence', $int),
        new DnD\AbilityScore('Wisdom', $wis),
        new DnD\AbilityScore('Charisma', $cha)
    ], // abilityScores
    2, // proficiencyBonus
    37, // hitPoints
    16, // armorClass
    1, // initiative
];

$sheet = new DnD\CharacterSheet(...$data);

$characterName = $sheet->getCharacterName();
$playerName = $sheet->getPlayerName();
$classes = $sheet->getClasses();
$background = $sheet->getBackground();
$race = $sheet->getRace();
$abilityScores = $sheet->getAbilityScores();
$proficiencyBonus = $sheet->getProficiencyBonus();
$hitPoints = $sheet->getHitPoints();
$armorClass = $sheet->getArmorClass();
$initiative = $sheet->getInitiative();

$mainClass = $sheet->getMainClass();
$savingThrows = $sheet->getSavingThrows();
$skills = $sheet->getSkills();
$passivePerception = $sheet->getPassivePerception();

$alignment = 'TODO'; // TODO: implement this
$useXp = false; // TODO: implement this
?>

<form class="character-sheet">
    
    <header>

        <section class="character-name">
            <label for="character-name">Character Name</label>
            <label>
                <input name="character-name" value="<?php echo $characterName; ?>"/>
            </label>
        </section>
        
        <section class="misc">
            <ul>
                
                <li>
                    <label for="class-level">Class & Level</label>
                    <label>
                        <?php
                        $classString = '';
                        foreach ($classes as $class) {
                            /** @var \DnD\CharacterClass $class */
                            $classString .= $class->getClassName() . ' ' . $class->getLevel();

                            // only add comma when not last and not single class
                            if (count($classes) > 1 && $class !== end($classes)) {
                                $classString .= ', ';
                            }
                        }
                        ?>
                        <input name="class-level" value="<?php echo $classString; ?>"/>
                    </label>
                </li>
                
                <li>
                    <label for="background">Background</label>
                    <label>
                        <input name="background" value="<?php echo $background->getName(); ?>"/>
                    </label>
                </li>
                
                <li>
                    <label for="player-name">Player Name</label>
                    <label>
                        <input name="player-name" value="<?php echo $playerName; ?>">
                    </label>
                </li>
                
                <li>
                    <label for="race">Race</label>
                    <label>
                        <input name="race" value="<?php echo $race->getName(); ?>"/>
                    </label>
                </li>
                
                <li>
                    <label for="alignment">Alignment</label>
                    <label>
                        <input name="alignment" value="<?php echo $alignment; ?>"/>
                    </label>
                </li>
                
                <?php if ($useXp): ?>
                    <li>
                        <label for="xp">Experience Points</label>
                        <label>
                            <input name="xp" value="0"/>
                        </label>
                    </li>
                <?php endif; ?>
                
            </ul>
        </section>
    </header>
    
    <main>
        <section>
            <section class="attributes">
                
                <div class="ability-scores">
                    <ul>
                        <?php foreach ($abilityScores as $abilityScore): ?>
                            <li>
                                <div class="score">
                                    <?php $name = $abilityScore->getName(); ?>
                                    <?php $score = $abilityScore->getScore(); ?>
                                    <?php $modifier = $abilityScore->getModifier(); ?>
                                    <label for="<?php echo $name; ?>score"><?php echo $name; ?></label>
                                    <label>
                                        <input name="<?php echo $name; ?>score" value="<?php echo $score; ?>"/>
                                    </label>
                                </div>
                                <div class="modifier">
                                    <label>
                                        <input name="<?php echo $name; ?>mod" value="<?php echo $modifier; ?>"/>
                                    </label>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="applications">

                    <!-- TODO: Add inspiration -->
                    <div class="inspiration box">
                        <div class="label-container">
                            <label for="inspiration">Inspiration</label>
                        </div>
                        <label>
                            <input name="inspiration" type="checkbox"/>
                        </label>
                    </div>
                    <!-- TODO: Add inspiration -->

                    <div class="proficiency-bonus box">
                        <div class="label-container">
                            <label for="proficiency-bonus">Proficiency Bonus</label>
                        </div>
                        <label>
                            <input name="proficiency-bonus" value="<?php echo $proficiencyBonus; ?>"/>
                        </label>
                    </div>

                    <div class="saving-throws list-section box">
                        <ul>
                            <?php foreach ($savingThrows as $name => $savingThrow): ?>
                                <?php $modifier = $savingThrow['modifier']; ?>
                                <?php $bonus = $savingThrow['bonus'] ?? $modifier; ?>
                                <?php $isProficient = $savingThrow['isProficient'] ?? false; ?>
                                <li>
                                    <label for="<?php echo $name; ?>-save"><?php echo $name; ?></label>
                                    <input name="<?php echo $name; ?>-save" value="<?php echo $bonus; ?>" type="text"/>

                                    <?php if ($isProficient): ?>
                                        <input name="<?php echo $name; ?>-save-prof" type="checkbox" checked/>
                                    <?php else: ?>
                                        <input name="<?php echo $name; ?>-save-prof" type="checkbox"/>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="label">
                            Saving Throws
                        </div>
                    </div>

                    <div class="skills list-section box">
                        <ul>
                            <?php foreach ($skills as $skill): ?>
                                <?php
                                $name = $skill['name'];
                                $ability = $skill['ability'];
                                $displayName = $ability['display_name'];
                                $shortName = $ability['short'];
                                $score = $skill['bonus'];
                                $isProficient = $skill['isProficient'];

                                foreach ($abilityScores as $abilityScore) if ($abilityScore->getName() == $displayName) {
                                    $score = $abilityScore->getModifier();
                                }

                                ?>
                                <li>
                                    <label for="Acrobatics">
                                        <?php echo $name; ?>
                                        <span class="skill">(<?php echo $shortName; ?>)</span>
                                    </label>
                                    <input name="<?php echo $name; ?>" value="<?php echo $score; ?>" type="text"/>

                                    <?php if ($isProficient): ?>
                                        <input name="<?php echo $name; ?>-prof" type="checkbox" checked/>
                                    <?php else: ?>
                                        <input name="<?php echo $name; ?>-prof" type="checkbox"/>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="label">
                            Skills
                        </div>
                    </div>

                </div>
            </section>

            <div class="passive-perception box">
                <div class="label-container">
                    <label for="passive-perception">Passive Wisdom (Perception)</label>
                </div>
                <input name="passive-perception" value="<?php echo $passivePerception; ?>"/>
            </div>

            <div class="other-proficiencies box textblock">
                <label for="other-proficiencies">Other Proficiencies & Languages</label>
                <textarea name="other-proficiencies"></textarea>
            </div>

        </section>

<!-- TODO: improve class names && update these in css -->
        <section>
            <section class="combat">
                <div class="armorclass">
                    <div>
                        <label for="ac">Armor Class</label><input name="ac" placeholder="10" type="text"/>
                    </div>
                </div>
                <div class="initiative">
                    <div>
                        <label for="initiative">Initiative</label><input name="initiative" placeholder="+0" type="text"/>
                    </div>
                </div>
                <div class="speed">
                    <div>
                        <label for="speed">Speed</label><input name="speed" placeholder="30" type="text"/>
                    </div>
                </div>
                <div class="hp">
                    <div class="regular">
                        <div class="max">
                            <label for="maxhp">Hit Point Maximum</label><input name="maxhp" placeholder="10" type="text"/>
                        </div>
                        <div class="current">
                            <label for="currenthp">Current Hit Points</label><input name="currenthp" type="text"/>
                        </div>
                    </div>
                    <div class="temporary">
                        <label for="temphp">Temporary Hit Points</label><input name="temphp" type="text"/>
                    </div>
                </div>
                <div class="hitdice">
                    <div>
                        <div class="total">
                            <label for="totalhd">Total</label><input name="totalhd" placeholder="2d10" type="text"/>
                        </div>
                        <div class="remaining">
                            <label for="remaininghd">Hit Dice</label><input name="remaininghd" type="text"/>
                        </div>
                    </div>
                </div>
                <div class="death-saves">
                    <div>
                        <div class="label">
                            <label>Death Saves</label>
                        </div>
                        <div class="marks">
                            <div class="deathsuccesses">
                                <label>Successes</label>
                                <div class="bubbles">
                                    <input name="deathsuccess1" type="checkbox"/>
                                    <input name="deathsuccess2" type="checkbox"/>
                                    <input name="deathsuccess3" type="checkbox"/>
                                </div>
                            </div>
                            <div class="deathfails">
                                <label>Failures</label>
                                <div class="bubbles">
                                    <input name="deathfail1" type="checkbox"/>
                                    <input name="deathfail2" type="checkbox"/>
                                    <input name="deathfail3" type="checkbox"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="attacksandspellcasting">
                <div>
                    <label>Attacks & Spellcasting</label>
                    <table>
                        <thead>
                        <tr>
                            <th>
                                Name
                            </th>
                            <th>
                                Atk Bonus
                            </th>
                            <th>
                                Damage/Type
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <input name="atkname1" type="text"/>
                            </td>
                            <td>
                                <input name="atkbonus1" type="text"/>
                            </td>
                            <td>
                                <input name="atkdamage1" type="text"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input name="atkname2" type="text"/>
                            </td>
                            <td>
                                <input name="atkbonus2" type="text"/>
                            </td>
                            <td>
                                <input name="atkdamage2" type="text"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input name="atkname3" type="text"/>
                            </td>
                            <td>
                                <input name="atkbonus3" type="text"/>
                            </td>
                            <td>
                                <input name="atkdamage3" type="text"/>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <textarea></textarea>
                </div>
            </section>
            <section class="equipment">
                <div>
                    <label>Equipment</label>
                    <div class="money">
                        <ul>
                            <li>
                                <label for="cp">cp</label><input name="cp"/>
                            </li>
                            <li>
                                <label for="sp">sp</label><input name="sp"/>
                            </li>
                            <li>
                                <label for="ep">ep</label><input name="ep"/>
                            </li>
                            <li>
                                <label for="gp">gp</label><input name="gp"/>
                            </li>
                            <li>
                                <label for="pp">pp</label><input name="pp"/>
                            </li>
                        </ul>
                    </div>
                    <textarea placeholder="Equipment list here"></textarea>
                </div>
            </section>
        </section>
        <section>
            <section class="flavor">
                <div class="personality">
                    <label for="personality">Personality</label><textarea name="personality"></textarea>
                </div>
                <div class="ideals">
                    <label for="ideals">Ideals</label><textarea name="ideals"></textarea>
                </div>
                <div class="bonds">
                    <label for="bonds">Bonds</label><textarea name="bonds"></textarea>
                </div>
                <div class="flaws">
                    <label for="flaws">Flaws</label><textarea name="flaws"></textarea>
                </div>
            </section>
            <section class="features">
                <div>
                    <label for="features">Features & Traits</label><textarea name="features"></textarea>
                </div>
            </section>
        </section>
    </main>
</form>
