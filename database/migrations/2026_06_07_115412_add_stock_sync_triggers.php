<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 🔄 TRIGGER: Kapag may bagong STOCK IN o STOCK OUT
        DB::unprepared('
            CREATE TRIGGER `trg_stock_after_insert` 
            AFTER INSERT ON `stock_transactions` 
            FOR EACH ROW 
            BEGIN
                -- Kung STOCK IN: DAGDAG sa stock at current_stock
                IF NEW.type = "IN" THEN
                    UPDATE medicines 
                    SET stock = stock + NEW.quantity, 
                        current_stock = current_stock + NEW.quantity 
                    WHERE id = NEW.medicine_id;
                END IF;

                -- Kung STOCK OUT: BAWAS sa stock at current_stock
                IF NEW.type = "OUT" THEN
                    UPDATE medicines 
                    SET stock = stock - NEW.quantity, 
                        current_stock = current_stock - NEW.quantity 
                    WHERE id = NEW.medicine_id;
                END IF;
            END;
        ');

        // 🔄 TRIGGER: Kapag inedit o binago ang transaksyon
        DB::unprepared('
            CREATE TRIGGER `trg_stock_after_update` 
            AFTER UPDATE ON `stock_transactions` 
            FOR EACH ROW 
            BEGIN
                -- BAWASIN muna ang LUMANG halaga
                IF OLD.type = "IN" THEN
                    UPDATE medicines 
                    SET stock = stock - OLD.quantity, 
                        current_stock = current_stock - OLD.quantity 
                    WHERE id = OLD.medicine_id;
                END IF;
                IF OLD.type = "OUT" THEN
                    UPDATE medicines 
                    SET stock = stock + OLD.quantity, 
                        current_stock = current_stock + OLD.quantity 
                    WHERE id = OLD.medicine_id;
                END IF;

                -- IDAGDAG ang BAGONG halaga
                IF NEW.type = "IN" THEN
                    UPDATE medicines 
                    SET stock = stock + NEW.quantity, 
                        current_stock = current_stock + NEW.quantity 
                    WHERE id = NEW.medicine_id;
                END IF;
                IF NEW.type = "OUT" THEN
                    UPDATE medicines 
                    SET stock = stock - NEW.quantity, 
                        current_stock = current_stock - NEW.quantity 
                    WHERE id = NEW.medicine_id;
                END IF;
            END;
        ');

        // 🔄 TRIGGER: Kapag binura ang transaksyon
        DB::unprepared('
            CREATE TRIGGER `trg_stock_after_delete` 
            AFTER DELETE ON `stock_transactions` 
            FOR EACH ROW 
            BEGIN
                IF OLD.type = "IN" THEN
                    UPDATE medicines 
                    SET stock = stock - OLD.quantity, 
                        current_stock = current_stock - OLD.quantity 
                    WHERE id = OLD.medicine_id;
                END IF;

                IF OLD.type = "OUT" THEN
                    UPDATE medicines 
                    SET stock = stock + OLD.quantity, 
                        current_stock = current_stock + OLD.quantity 
                    WHERE id = OLD.medicine_id;
                END IF;
            END;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tanggalin ang mga trigger kapag binawi ang migration
        DB::unprepared('DROP TRIGGER IF EXISTS `trg_stock_after_insert`');
        DB::unprepared('DROP TRIGGER IF EXISTS `trg_stock_after_update`');
        DB::unprepared('DROP TRIGGER IF EXISTS `trg_stock_after_delete`');
    }
};