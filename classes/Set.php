<?php
Class Set {
    // Properties
    public string $set_name;
    public int $set_ID;

    public string $set_Username;
    public int $set_card_count;

    public string $set_filter_1;
    public string $set_filter_2;
    public string $set_filter_3;
    public int $set_User_ID;
    public bool $set_Private = true;
    public array $set_Cards = [];

    // Getters and Setters
    public function getSetName(): string {
        return $this->set_name;
    }

    public function setSetName(string $set_name): void {
        $this->set_name = $set_name;
    }
    public function getSetUsername(): string {
        return $this->set_Username;
    }

    public function setSetUsername(string $Username): void {
        $this->set_Username = $Username;
    }
    public function getSetID(): int {
        return $this->set_ID;
    }

    public function setSetID(int $set_ID): void {
        $this->set_ID = $set_ID;
    }

    public function getSetCardCount(): int {
        return $this->set_card_count;
    }

    public function setSetCardCount(int $set_card_count): void {
        $this->set_card_count = $set_card_count;
    }

    public function getSetFilter1(): string {
        return $this->set_filter_1;
    }

    public function setSetFilter1(string $set_filter_1): void {
        $this->set_filter_1 = $set_filter_1;
    }

    public function getSetFilter2(): string {
        return $this->set_filter_2;
    }

    public function setSetFilter2(string $set_filter_2): void {
        $this->set_filter_2 = $set_filter_2;
    }

    public function getSetFilter3(): string {
        return $this->set_filter_3;
    }

    public function setSetFilter3(string $set_filter_3): void {
        $this->set_filter_3 = $set_filter_3;
    }

    public function getSetUserID(): int {
        return $this->set_User_ID;
    }

    public function setSetUserID(int $set_User_ID): void {
        $this->set_User_ID = $set_User_ID;
    }

    public function getSetPrivate(): bool {
        return $this->set_Private;
    }

    public function setSetPrivate(bool $set_Private): void {
        $this->set_Public = $set_Private;
    }

    public function getSetCards(): array {
        return $this->set_Cards;
    }

    public function setSetCards(array $set_Cards): void {
        $this->set_Cards = $set_Cards;
    }
}
?>