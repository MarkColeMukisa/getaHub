// Central real-time bill event handling: activity feed + toast notifications
(function(){
    function ensureToastContainer(){
        let host = document.getElementById('toastHost');
        if(!host){
            host = document.createElement('div');
            host.id = 'toastHost';
            host.className = 'fixed top-4 right-4 z-50 space-y-2 max-w-sm';
            document.body.appendChild(host);
        }
        return host;
    }
    function realtimeToast(text){
        const host = ensureToastContainer();
        const el = document.createElement('div');
        el.className = 'bg-white shadow-lg border border-primary/30 rounded-lg px-4 py-3 text-sm flex items-start gap-3 transition duration-300';
        el.innerHTML = `<span class="mt-0.5 text-primary">💧</span><div class="flex-1">${text}</div><button class="text-gray-400 hover:text-primary" aria-label="Close">&times;</button>`;
        el.querySelector('button').onclick = () => el.remove();
        host.prepend(el);
        setTimeout(()=>{el.classList.add('opacity-0','translate-x-4'); setTimeout(()=>el.remove(),300);},7000);
    }
    function appendActivityHtml(e){
        const panels = document.querySelectorAll('.realtime-activity-panel');
        if (!panels.length) return;
        panels.forEach(activity => {
            const item = document.createElement('div');
            item.className = 'rounded bg-primary/10 border border-primary/20 px-3 py-2 mb-1 shadow-sm';
            item.innerHTML = `<b>New Bill:</b> <span class='text-primary'>${e.tenant}</span> (Room ${e.room})<br><span class='text-xs text-gray-500'>UGX ${Number(e.amount).toLocaleString()} • ${new Date(e.created_at).toLocaleTimeString()}</span>`;
            activity.prepend(item);
            while (activity.children.length > 20) activity.removeChild(activity.lastChild);
        });
    }
    if (window.Echo) {
        try {
            window.Echo.channel('public.metrics').listen('.bill.created', (e)=>{
                const msg = `New bill #${e.id} for ${e.tenant || 'Tenant'} (Room ${e.room || '?'}) - UGX ${Number(e.amount).toLocaleString()}`;
                realtimeToast(msg);
                appendActivityHtml(e);
            });
        } catch(err){
            console.warn('Echo real-time hookup failed', err);
        }
    }
})();
