<?php
/** Migration quarantine only; the retired custom tables never grant access. */
function legacyAdMigrationPending(PDO $db,string $column,int|string $value): bool {
    if(!in_array($column,['email','analyst_id','user_id'],true))throw new LogicException('Invalid identity selector');
    try{
        $s=$db->prepare("SELECT id FROM ad_profiles WHERE $column=? AND (user_id IS NOT NULL OR analyst_id IS NOT NULL)");$s->execute([$value]);$ids=$s->fetchAll(PDO::FETCH_COLUMN);
        foreach($ids as$id){
            try{$m=$db->prepare('SELECT 1 FROM legacy_ad_profile_migrations WHERE legacy_profile_id=?');$m->execute([$id]);if(!$m->fetchColumn())return true;}
            catch(PDOException$e){if($e->getCode()==='42S02')return true;throw$e;}
        }
        return false;
    }catch(PDOException$e){if($e->getCode()==='42S02')return false;throw$e;}
}
