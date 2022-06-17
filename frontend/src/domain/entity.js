export class Entity
{
    constructor (attrs) {
        for (const [key, value] of Object.entries(attrs)) {
            this[key] = value
        }
    }
}

export default Entity;