import Entity from './entity.js'

export class Publication extends Entity
{
    get name () {
        return this.title ? this.title : `${this.coding_system.name}:${this.code}`;
    }
}