import { computed } from 'vue'

export const parseHashData = (hash) => {
    const hashString = hash.substr(1);
    if (!hashString) {
        return {};
    }
    const parsed = {};
    hashString.split('&')
            .forEach(phrase => {
                const [key, value = true] = [...phrase.split('=')]
                parsed[key] = value;
            });
    return parsed;
}

export default function (route, router) {
    const hashData = computed(() => {
        return parseHashData(route.hash)
    });

    const stringifyHashData = (hashObj) => {
        const hashString = Object.entries(hashObj).map(([key, value]) => {
            if (value === true) {
                return key
            }

            return `${key}=${value}`
        }).join('&');
    
        if (hashString) {
            return '#'+hashString
        }
        return ''
    };

    
    return {
        hashData,
        clearHash: () => {
            router.replace({name: route.name, hash: null})
        },
        removeFromHash: (removeKey) => {
            const newHashData = {}
            Object.entries(hashData.value).forEach(([key, value]) => {
                if (key != removeKey) {
                    newHashData[key] = value
                }
            })

            router.replace({name: route.name, hash: stringifyHashData(newHashData)});
        },
        addToHash: (key, value = true) => {
            const temp = hashData.value;
            temp[key] = value;

            router.replace({name: route.name, hash: stringifyHashData(temp)})
        }
    }
}