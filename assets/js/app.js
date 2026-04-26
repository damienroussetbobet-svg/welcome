const {
  useState,
  useEffect,
  useRef
} = React;
const D = window.SITE_DATA;

/* ── Lucide icon helper ── */
function Icon({
  name,
  size = 16,
  color = "currentColor",
  strokeWidth = 2
}) {
  const ref = useRef(null);
  useEffect(() => {
    if (ref.current && window.lucide) {
      const camel = name.replace(/-([a-z])/g, (_, c) => c.toUpperCase());
      const key = camel.charAt(0).toUpperCase() + camel.slice(1);
      const iconData = window.lucide[key] || window.lucide[name];
      if (iconData) {
        const [, attrs, children] = iconData;
        const svgAttrs = {
          ...attrs,
          width: size,
          height: size,
          stroke: color,
          "stroke-width": strokeWidth
        };
        const attrStr = Object.entries(svgAttrs).map(([k, v]) => `${k}="${v}"`).join(" ");
        const inner = (children || []).map(([t, a]) => {
          const aStr = Object.entries(a).map(([k, v]) => `${k}="${v}"`).join(" ");
          return `<${t} ${aStr}/>`;
        }).join("");
        ref.current.innerHTML = `<svg ${attrStr}>${inner}</svg>`;
      }
    }
  }, [name, size, color]);
  return /*#__PURE__*/React.createElement("span", {
    ref: ref,
    style: {
      display: "inline-flex",
      alignItems: "center",
      justifyContent: "center"
    }
  });
}
function IconBox({
  name,
  color,
  size = 36,
  iconSize = 18
}) {
  return /*#__PURE__*/React.createElement("div", {
    style: {
      width: size,
      height: size,
      borderRadius: Math.round(size * 0.28),
      background: color + "22",
      display: "flex",
      alignItems: "center",
      justifyContent: "center",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(Icon, {
    name: name,
    size: iconSize,
    color: color,
    strokeWidth: 2
  }));
}
const T = {
  navy: "#1B3A7A",
  cyan: "#00A8D6",
  green: "#5CB85C",
  orange: "#F5A020",
  purple: "#8A6FE8",
  bg: "#E8EDFF",
  white: "#FFFFFF",
  dark: "#0F1E45",
  muted: "#6B7BA8",
  pill: "#BDD0FF"
};
const shadow = "0 4px 24px rgba(27,58,122,0.10)";
const shadowSm = "0 2px 10px rgba(27,58,122,0.08)";
const NAV = [{
  id: "bienvenue",
  icon: "👋",
  label: "Bienvenue"
}, {
  id: "service",
  icon: "🏥",
  label: "Notre service"
}, {
  id: "equipe",
  icon: "👥",
  label: "Équipe"
}, {
  id: "annuaire",
  icon: "📒",
  label: "Annuaire"
}, {
  id: "outils",
  icon: "🛠️",
  label: "Outils"
}, {
  id: "procedures",
  icon: "📋",
  label: "Procédures"
}, {
  id: "contacts",
  icon: "📞",
  label: "Contacts"
}, {
  id: "planning",
  icon: "📅",
  label: "Planning"
}, {
  id: "pratique",
  icon: "ℹ️",
  label: "Infos pratiques"
}, {
  id: "ressources",
  icon: "🔗",
  label: "Ressources"
}, {
  id: "faq",
  icon: "❓",
  label: "FAQ"
}];
const goTo = id => {
  const el = document.getElementById(id);
  if (el) window.scrollTo({
    top: el.getBoundingClientRect().top + window.scrollY - 28,
    behavior: "smooth"
  });
};

/* ── Sidebar ── */
function Sidebar({
  active
}) {
  const cfg = D.config || {};
  const helpEmail = cfg.helpdesk_email || 'helpdesk@chu-angers.fr';
  const helpDispo = cfg.helpdesk_disponibilite || 'lun–ven 7h30–18h30';
  return /*#__PURE__*/React.createElement("aside", {
    style: {
      width: 230,
      minWidth: 230,
      background: T.white,
      borderRadius: "0 24px 24px 0",
      boxShadow: shadow,
      position: "sticky",
      top: 0,
      height: "100vh",
      display: "flex",
      flexDirection: "column",
      overflowY: "auto",
      zIndex: 10
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      padding: "24px 20px 16px"
    }
  }, /*#__PURE__*/React.createElement("img", {
    src: "logo-chu.png",
    alt: "CHU Angers",
    style: {
      width: 84
    }
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      marginTop: 10,
      background: T.pill,
      borderRadius: 8,
      padding: "6px 12px",
      display: "inline-block"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: 10.5,
      fontWeight: 700,
      color: T.navy,
      letterSpacing: "0.06em"
    }
  }, "LIVRET D'ACCUEIL \xB7 DSN"))), /*#__PURE__*/React.createElement("nav", {
    style: {
      flex: 1,
      padding: "4px 12px"
    }
  }, NAV.map(n => /*#__PURE__*/React.createElement("button", {
    key: n.id,
    onClick: () => goTo(n.id),
    style: {
      display: "flex",
      alignItems: "center",
      gap: 10,
      width: "100%",
      padding: "9px 12px",
      borderRadius: 12,
      border: "none",
      background: active === n.id ? T.dark : "transparent",
      color: active === n.id ? T.white : T.muted,
      fontWeight: active === n.id ? 700 : 400,
      fontSize: 13.5,
      fontFamily: "'Plus Jakarta Sans', sans-serif",
      cursor: "pointer",
      textAlign: "left",
      marginBottom: 2,
      transition: "all 0.15s"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: 15
    }
  }, n.icon), n.label))), /*#__PURE__*/React.createElement("div", {
    style: {
      margin: "12px 12px 16px",
      background: T.pill,
      borderRadius: 14,
      padding: "14px"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 11.5,
      fontWeight: 700,
      color: T.navy,
      marginBottom: 4
    }
  }, "Une question ?"), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 11,
      color: T.muted,
      marginBottom: 10,
      lineHeight: 1.4
    }
  }, "Le helpdesk est disponible ", helpDispo, "."), /*#__PURE__*/React.createElement("a", {
    href: `mailto:${helpEmail}`,
    style: {
      display: "block",
      background: T.dark,
      color: T.white,
      borderRadius: 8,
      padding: "7px 12px",
      fontSize: 12,
      fontWeight: 700,
      textAlign: "center"
    }
  }, helpEmail)), /*#__PURE__*/React.createElement("div", {
    style: {
      padding: "0 12px 14px",
      textAlign: "center"
    }
  }, /*#__PURE__*/React.createElement("a", {
    href: "admin/",
    style: {
      fontSize: 11,
      color: T.muted,
      textDecoration: "underline"
    }
  }, "\u2699 Administration")));
}
const Card = ({
  children,
  style = {},
  color
}) => /*#__PURE__*/React.createElement("div", {
  style: {
    background: color || T.white,
    borderRadius: 20,
    boxShadow: shadowSm,
    padding: "22px 24px",
    ...style
  }
}, children);
const PillTag = ({
  label,
  color = T.cyan
}) => /*#__PURE__*/React.createElement("span", {
  style: {
    display: "inline-block",
    background: color + "22",
    color,
    borderRadius: 20,
    padding: "3px 10px",
    fontSize: 11,
    fontWeight: 700,
    letterSpacing: "0.04em"
  }
}, label);
const DarkBtn = ({
  children,
  href = "#",
  style = {}
}) => /*#__PURE__*/React.createElement("a", {
  href: href,
  style: {
    display: "inline-block",
    background: T.dark,
    color: T.white,
    borderRadius: 24,
    padding: "10px 22px",
    fontSize: 13,
    fontWeight: 700,
    ...style
  }
}, children);
function Sec({
  id,
  label,
  children
}) {
  return /*#__PURE__*/React.createElement("section", {
    id: id,
    "data-screen-label": label,
    style: {
      marginBottom: 20
    }
  }, children);
}

/* ── Texte par défaut du mot d'accueil ── */
const MOT_ACCUEIL_DEFAULT = `Nous sommes heureux de vous accueillir parmi nous et nous espérons que vous vous plairez dans votre nouvel environnement de travail.

Voici quelques informations importantes pour vous aider à vous orienter et à vous familiariser avec notre service :

Notre service informatique est responsable de la gestion et de l'entretien de tous les systèmes informatiques de l'établissement, y compris les ordinateurs, les serveurs, les réseaux et les logiciels. Nous travaillons en étroite collaboration avec tous les services de l'établissement pour s'assurer que les systèmes informatiques fonctionnent de manière efficace et sécurisée.

Vous serez amené à travailler sur divers projets informatiques, tels que la mise en place de nouveaux systèmes, la maintenance et le dépannage des systèmes existants, ou encore la formation des utilisateurs aux différents outils informatiques.

Vous aurez également la responsabilité de respecter les protocoles de sécurité informatique de l'établissement, en veillant notamment à la confidentialité des données et à la protection contre les virus et les attaques informatiques.

Nous avons établi un certain nombre de règles et de procédures pour assurer le bon fonctionnement de notre service. Nous vous demandons de les respecter afin de contribuer à la qualité de notre travail et à la satisfaction de nos utilisateurs.

En cas de problème ou de question, n'hésitez pas à vous adresser à votre manager ou à un de vos collègues. Nous sommes là pour vous aider et vous soutenir dans votre travail.

En espérant que votre intégration se passera bien et que vous apprécierez votre nouvel environnement de travail, nous vous souhaitons une excellente année professionnelle.`;

/* ── Modale mot d'accueil ── */
function MotAccueilModal({
  titre,
  texte,
  onClose
}) {
  useEffect(() => {
    const onKey = e => {
      if (e.key === 'Escape') onClose();
    };
    document.addEventListener('keydown', onKey);
    return () => document.removeEventListener('keydown', onKey);
  }, []);
  const isHtml = /<[a-z]/i.test(texte);
  const paragraphs = isHtml ? [] : texte.split(/\n\n+/).filter(p => p.trim());
  return /*#__PURE__*/React.createElement("div", {
    onClick: e => e.target === e.currentTarget && onClose(),
    style: {
      position: "fixed",
      inset: 0,
      background: "rgba(5,10,30,0.75)",
      zIndex: 2000,
      display: "flex",
      alignItems: "center",
      justifyContent: "center",
      backdropFilter: "blur(8px)",
      padding: "20px"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      borderRadius: 24,
      maxWidth: 680,
      width: "100%",
      maxHeight: "88vh",
      display: "flex",
      flexDirection: "column",
      boxShadow: "0 32px 80px rgba(0,0,0,0.38)",
      overflow: "hidden"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      background: T.dark,
      padding: "22px 32px",
      display: "flex",
      alignItems: "center",
      justifyContent: "space-between",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      gap: 10
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: 18
    }
  }, "\uD83D\uDCC4"), /*#__PURE__*/React.createElement("h2", {
    style: {
      fontSize: 17,
      fontWeight: 800,
      color: T.white,
      margin: 0,
      letterSpacing: "0.01em"
    }
  }, titre)), /*#__PURE__*/React.createElement("button", {
    onClick: onClose,
    style: {
      width: 32,
      height: 32,
      borderRadius: "50%",
      background: "rgba(255,255,255,0.12)",
      border: "none",
      cursor: "pointer",
      fontSize: 20,
      lineHeight: 1,
      color: "rgba(255,255,255,0.85)",
      display: "flex",
      alignItems: "center",
      justifyContent: "center"
    }
  }, "\xD7")), /*#__PURE__*/React.createElement("div", {
    style: {
      overflowY: "auto",
      padding: "36px 40px 40px",
      background: "#F7F9FF"
    }
  }, isHtml ? /*#__PURE__*/React.createElement("div", {
    className: "mot-accueil-body",
    dangerouslySetInnerHTML: {
      __html: texte
    }
  }) : paragraphs.map((p, i) => /*#__PURE__*/React.createElement("p", {
    key: i,
    style: {
      fontSize: i === 0 ? 15.5 : 14.5,
      fontWeight: i === 0 ? 600 : 400,
      color: i === 0 ? T.dark : "#3D4A6A",
      lineHeight: 1.85,
      marginBottom: i < paragraphs.length - 1 ? 20 : 0
    }
  }, p)))));
}

/* ── Modale vidéo ── */
function VideoModal({
  src,
  onClose
}) {
  useEffect(() => {
    const onKey = e => {
      if (e.key === 'Escape') onClose();
    };
    document.addEventListener('keydown', onKey);
    return () => document.removeEventListener('keydown', onKey);
  }, []);
  return /*#__PURE__*/React.createElement("div", {
    onClick: e => e.target === e.currentTarget && onClose(),
    style: {
      position: "fixed",
      inset: 0,
      background: "rgba(5,10,30,0.88)",
      zIndex: 2000,
      display: "flex",
      alignItems: "center",
      justifyContent: "center",
      backdropFilter: "blur(6px)"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      maxWidth: "92vw"
    }
  }, /*#__PURE__*/React.createElement("video", {
    src: src,
    controls: true,
    autoPlay: true,
    style: {
      display: "block",
      maxWidth: "92vw",
      maxHeight: "82vh",
      borderRadius: 16,
      boxShadow: "0 24px 64px rgba(0,0,0,0.6)"
    }
  }), /*#__PURE__*/React.createElement("button", {
    onClick: onClose,
    style: {
      position: "absolute",
      top: -14,
      right: -14,
      width: 34,
      height: 34,
      borderRadius: "50%",
      background: T.white,
      border: "none",
      cursor: "pointer",
      fontSize: 20,
      fontWeight: 700,
      color: T.dark,
      display: "flex",
      alignItems: "center",
      justifyContent: "center",
      boxShadow: "0 4px 12px rgba(0,0,0,0.3)"
    }
  }, "\xD7")));
}

/* ── Sections ── */
function SBienvenue() {
  const [videoOpen, setVideoOpen] = useState(false);
  const [motOpen, setMotOpen] = useState(false);
  const cfg = D.config || {};
  const subtitle = cfg.bienvenue_subtitle || 'DSN · Direction du Système Numérique';
  const title = cfg.bienvenue_title || "Bienvenue dans\nl'équipe DSN 👋";
  const text = cfg.bienvenue_text || '';
  const cta = cfg.bienvenue_cta || 'Découvrir le service →';
  const motBtn = cfg.bienvenue_mot_btn || "Mot d'accueil";
  const motTitre = cfg.bienvenue_mot_titre || "Mot d'accueil";
  const motTexte = cfg.bienvenue_mot_accueil || MOT_ACCUEIL_DEFAULT;
  const videoFile = cfg.bienvenue_video || '';
  const videoSrc = videoFile ? `uploads/${videoFile}` : null;
  const stats = [[cfg.bienvenue_stat1_value || '~180', cfg.bienvenue_stat1_label || 'agents DSN', T.cyan], [cfg.bienvenue_stat2_value || '24h/24', cfg.bienvenue_stat2_label || 'astreintes', T.orange], [cfg.bienvenue_stat3_value || '3 pôles', cfg.bienvenue_stat3_label || "d'expertise", T.purple]];
  const titleLines = title.split('\n');
  return /*#__PURE__*/React.createElement(Sec, {
    id: "bienvenue",
    label: "01 Bienvenue"
  }, videoOpen && videoSrc && /*#__PURE__*/React.createElement(VideoModal, {
    src: videoSrc,
    onClose: () => setVideoOpen(false)
  }), motOpen && /*#__PURE__*/React.createElement(MotAccueilModal, {
    titre: motTitre,
    texte: motTexte,
    onClose: () => setMotOpen(false)
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      background: T.pill,
      borderRadius: 24,
      padding: "36px 36px 28px",
      marginBottom: 12,
      position: "relative",
      overflow: "hidden"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      right: 32,
      top: -40,
      width: 200,
      height: 200,
      borderRadius: "50%",
      background: "rgba(27,58,122,0.10)",
      pointerEvents: "none"
    }
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      right: -20,
      bottom: -30,
      width: 140,
      height: 140,
      borderRadius: "50%",
      background: "rgba(0,168,214,0.14)",
      pointerEvents: "none"
    }
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      zIndex: 1
    }
  }, /*#__PURE__*/React.createElement(PillTag, {
    label: subtitle,
    color: T.navy
  }), /*#__PURE__*/React.createElement("h1", {
    style: {
      fontSize: 34,
      fontWeight: 800,
      color: T.dark,
      lineHeight: 1.2,
      margin: "14px 0 12px",
      maxWidth: 480
    }
  }, titleLines.map((line, i) => /*#__PURE__*/React.createElement(React.Fragment, {
    key: i
  }, line, i < titleLines.length - 1 && /*#__PURE__*/React.createElement("br", null)))), /*#__PURE__*/React.createElement("p", {
    style: {
      fontSize: 14,
      color: T.muted,
      lineHeight: 1.65,
      maxWidth: 440,
      marginBottom: 22
    }
  }, text), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      gap: 12,
      flexWrap: "wrap",
      alignItems: "center"
    }
  }, videoSrc ? /*#__PURE__*/React.createElement("button", {
    onClick: () => setVideoOpen(true),
    style: {
      display: "inline-flex",
      alignItems: "center",
      gap: 10,
      background: T.dark,
      color: T.white,
      borderRadius: 24,
      padding: "10px 22px",
      fontSize: 13,
      fontWeight: 700,
      border: "none",
      cursor: "pointer",
      fontFamily: "'Plus Jakarta Sans',sans-serif"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      width: 28,
      height: 28,
      borderRadius: "50%",
      background: "rgba(255,255,255,0.15)",
      display: "inline-flex",
      alignItems: "center",
      justifyContent: "center"
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: "10",
    height: "12",
    viewBox: "0 0 10 12",
    fill: "white"
  }, /*#__PURE__*/React.createElement("polygon", {
    points: "0,0 10,6 0,12"
  }))), cta) : /*#__PURE__*/React.createElement(DarkBtn, {
    href: "#service"
  }, cta), /*#__PURE__*/React.createElement("button", {
    onClick: () => setMotOpen(true),
    style: {
      display: "inline-flex",
      alignItems: "center",
      gap: 8,
      background: "transparent",
      color: T.dark,
      borderRadius: 24,
      padding: "9px 20px",
      fontSize: 13,
      fontWeight: 700,
      border: `2px solid ${T.navy}55`,
      cursor: "pointer",
      fontFamily: "'Plus Jakarta Sans',sans-serif"
    }
  }, "\uD83D\uDCC4 ", motBtn)))), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "grid",
      gridTemplateColumns: "repeat(3,1fr)",
      gap: 12
    }
  }, stats.map(([v, l, c]) => /*#__PURE__*/React.createElement(Card, {
    key: l,
    color: c + "18",
    style: {
      padding: "18px 20px",
      border: `1.5px solid ${c}22`
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 26,
      fontWeight: 800,
      color: c,
      marginBottom: 2
    }
  }, v), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 12,
      color: T.muted
    }
  }, l)))));
}
function SService() {
  const missions = [{
    t: "Infrastructure & Réseaux",
    d: "Serveurs, réseau, téléphonie et équipements.",
    color: T.cyan,
    icon: "server"
  }, {
    t: "Applications Métier",
    d: "Logiciels cliniques et administratifs.",
    color: T.purple,
    icon: "layout-grid"
  }, {
    t: "Support utilisateurs",
    d: "Helpdesk, assistance, formation.",
    color: T.orange,
    icon: "headphones"
  }, {
    t: "Sécurité & Conformité",
    d: "RGPD, cybersécurité, accès.",
    color: T.green,
    icon: "shield-check"
  }];
  return /*#__PURE__*/React.createElement(Sec, {
    id: "service",
    label: "02 Service"
  }, /*#__PURE__*/React.createElement(Card, null, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      gap: 10,
      marginBottom: 16
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: 22
    }
  }, "\uD83C\uDFE5"), /*#__PURE__*/React.createElement("h2", {
    style: {
      fontSize: 20,
      fontWeight: 800,
      color: T.dark
    }
  }, "La DSN en bref")), /*#__PURE__*/React.createElement("p", {
    style: {
      fontSize: 13.5,
      color: T.muted,
      lineHeight: 1.65,
      marginBottom: 20,
      maxWidth: 520
    }
  }, "La DSN assure la transformation num\xE9rique du CHU d'Angers et garantit le bon fonctionnement de l'ensemble des outils informatiques au service des soins."), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "grid",
      gridTemplateColumns: "repeat(2,1fr)",
      gap: 10,
      marginBottom: 14
    }
  }, missions.map(m => /*#__PURE__*/React.createElement("div", {
    key: m.t,
    style: {
      display: "flex",
      alignItems: "flex-start",
      gap: 12,
      padding: "14px 16px",
      background: m.color + "14",
      borderRadius: 14
    }
  }, /*#__PURE__*/React.createElement(IconBox, {
    name: m.icon,
    color: m.color
  }), /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("div", {
    style: {
      fontWeight: 700,
      fontSize: 13.5,
      color: T.dark,
      marginBottom: 3
    }
  }, m.t), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 12,
      color: T.muted,
      lineHeight: 1.45
    }
  }, m.d))))), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      gap: 10,
      padding: "12px 16px",
      background: T.pill,
      borderRadius: 12,
      fontSize: 13,
      color: T.navy
    }
  }, /*#__PURE__*/React.createElement("span", null, "\uD83D\uDCCD"), /*#__PURE__*/React.createElement("span", null, "B\xE2timent administratif, RDC \xB7 4 rue Larrey, 49933 Angers Cedex 9 \xB7 ", /*#__PURE__*/React.createElement("strong", null, "Acc\xE8s badge requis")))));
}

/* ── Org chart (modal) ── */
function OrgTreeNode({
  node,
  depth = 0
}) {
  const [expanded, setExpanded] = useState(depth < 2);
  const [hovered, setHovered] = useState(false);
  const hasChildren = node.children && node.children.length > 0;
  const hasContact = node.ext || node.poste2 || node.tel || node.email;
  return /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      flexDirection: "column",
      alignItems: "center"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative"
    },
    onMouseEnter: () => setHovered(true),
    onMouseLeave: () => setHovered(false)
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      background: T.white,
      border: `2px solid ${node.couleur}44`,
      borderRadius: 12,
      padding: "8px 14px",
      display: "flex",
      alignItems: "center",
      gap: 10,
      minWidth: 160,
      maxWidth: 200,
      boxShadow: `0 2px 8px ${node.couleur}22`,
      cursor: hasChildren ? "pointer" : "default",
      transition: "all 0.15s"
    },
    onClick: () => hasChildren && setExpanded(!expanded)
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      width: 32,
      height: 32,
      borderRadius: "50%",
      background: node.couleur,
      color: T.white,
      fontSize: 10,
      fontWeight: 800,
      display: "flex",
      alignItems: "center",
      justifyContent: "center",
      flexShrink: 0
    }
  }, node.i), /*#__PURE__*/React.createElement("div", {
    style: {
      minWidth: 0,
      flex: 1
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 11.5,
      fontWeight: 700,
      color: T.dark,
      lineHeight: 1.2
    }
  }, node.n), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 10,
      color: T.muted,
      lineHeight: 1.3,
      marginTop: 1
    }
  }, node.r)), hasContact && /*#__PURE__*/React.createElement("div", {
    style: {
      width: 16,
      height: 16,
      borderRadius: "50%",
      background: node.couleur + "33",
      display: "flex",
      alignItems: "center",
      justifyContent: "center",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(Icon, {
    name: "Phone",
    size: 8,
    color: node.couleur
  })), hasChildren && /*#__PURE__*/React.createElement("div", {
    style: {
      width: 18,
      height: 18,
      borderRadius: "50%",
      background: node.couleur + "22",
      display: "flex",
      alignItems: "center",
      justifyContent: "center",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(Icon, {
    name: expanded ? "ChevronUp" : "ChevronDown",
    size: 10,
    color: node.couleur
  }))), hasContact && hovered && /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      top: "calc(100% + 8px)",
      left: "50%",
      transform: "translateX(-50%)",
      background: T.dark,
      color: T.white,
      borderRadius: 10,
      padding: "8px 12px",
      fontSize: 11,
      lineHeight: 1.9,
      whiteSpace: "nowrap",
      zIndex: 200,
      boxShadow: "0 4px 20px rgba(0,0,0,0.35)",
      pointerEvents: "none"
    }
  }, node.ext && /*#__PURE__*/React.createElement("div", null, "\u260E ", node.ext), node.poste2 && /*#__PURE__*/React.createElement("div", null, "\uD83D\uDCDF ", node.poste2), node.tel && /*#__PURE__*/React.createElement("div", null, "\uD83D\uDCF1 ", node.tel), node.email && /*#__PURE__*/React.createElement("div", null, "\u2709 ", node.email))), hasChildren && expanded && /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      flexDirection: "column",
      alignItems: "center"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      width: 2,
      height: 16,
      background: node.couleur + "55"
    }
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "flex-start",
      gap: 0,
      position: "relative"
    }
  }, node.children.length > 1 && /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      top: 0,
      left: `calc(50% / ${node.children.length})`,
      right: `calc(50% / ${node.children.length})`,
      height: 2,
      background: node.couleur + "44"
    }
  }), node.children.map((child, ci) => /*#__PURE__*/React.createElement("div", {
    key: ci,
    style: {
      display: "flex",
      flexDirection: "column",
      alignItems: "center",
      padding: "0 8px"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      width: 2,
      height: 16,
      background: child.couleur + "55"
    }
  }), /*#__PURE__*/React.createElement(OrgTreeNode, {
    node: child,
    depth: depth + 1
  }))))));
}
function OrgModal({
  onClose,
  orgTree
}) {
  const [zoom, setZoom] = useState(0.85);
  return /*#__PURE__*/React.createElement("div", {
    style: {
      position: "fixed",
      inset: 0,
      background: "rgba(10,20,50,0.7)",
      zIndex: 1000,
      display: "flex",
      flexDirection: "column",
      backdropFilter: "blur(4px)"
    },
    onClick: e => e.target === e.currentTarget && onClose()
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      background: T.white,
      margin: "20px",
      borderRadius: 24,
      flex: 1,
      display: "flex",
      flexDirection: "column",
      overflow: "hidden",
      boxShadow: "0 24px 64px rgba(0,0,0,0.3)"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      gap: 12,
      padding: "16px 24px",
      borderBottom: `1px solid ${T.bg}`
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: 20
    }
  }, "\uD83C\uDFD7\uFE0F"), /*#__PURE__*/React.createElement("div", {
    style: {
      flex: 1
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontWeight: 800,
      fontSize: 17,
      color: T.dark
    }
  }, "Organigramme complet \u2013 DSN CHU Angers"), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 12,
      color: T.muted
    }
  }, "Cliquez sur un n\u0153ud pour d\xE9plier/replier ses sous-\xE9quipes")), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      gap: 8
    }
  }, /*#__PURE__*/React.createElement("button", {
    onClick: () => setZoom(z => Math.max(0.4, z - 0.1)),
    style: {
      width: 28,
      height: 28,
      borderRadius: 8,
      border: `1.5px solid ${T.bg}`,
      background: T.bg,
      cursor: "pointer",
      fontSize: 16,
      display: "flex",
      alignItems: "center",
      justifyContent: "center",
      color: T.dark
    }
  }, "\u2212"), /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: 12,
      fontWeight: 600,
      color: T.muted,
      minWidth: 36,
      textAlign: "center"
    }
  }, Math.round(zoom * 100), "%"), /*#__PURE__*/React.createElement("button", {
    onClick: () => setZoom(z => Math.min(1.4, z + 0.1)),
    style: {
      width: 28,
      height: 28,
      borderRadius: 8,
      border: `1.5px solid ${T.bg}`,
      background: T.bg,
      cursor: "pointer",
      fontSize: 16,
      display: "flex",
      alignItems: "center",
      justifyContent: "center",
      color: T.dark
    }
  }, "+")), /*#__PURE__*/React.createElement("button", {
    onClick: onClose,
    style: {
      width: 36,
      height: 36,
      borderRadius: 10,
      border: "none",
      background: T.bg,
      cursor: "pointer",
      fontSize: 18,
      color: T.muted,
      display: "flex",
      alignItems: "center",
      justifyContent: "center"
    }
  }, "\xD7")), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      gap: 16,
      padding: "10px 24px",
      borderBottom: `1px solid ${T.bg}`,
      flexWrap: "wrap",
      alignItems: "center"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: 11,
      color: T.muted,
      fontWeight: 600,
      marginRight: 4
    }
  }, "Domaines :"), (D.domaines || []).map(d => /*#__PURE__*/React.createElement("div", {
    key: d.nom,
    style: {
      display: "flex",
      alignItems: "center",
      gap: 6
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      width: 10,
      height: 10,
      borderRadius: "50%",
      background: d.couleur
    }
  }), /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: 11.5,
      color: T.muted,
      fontWeight: 500
    }
  }, d.nom))), /*#__PURE__*/React.createElement("span", {
    style: {
      marginLeft: "auto",
      fontSize: 11,
      color: T.muted
    }
  }, /*#__PURE__*/React.createElement(Icon, {
    name: "Phone",
    size: 10,
    color: T.muted
  }), " = infos de contact au survol")), /*#__PURE__*/React.createElement("div", {
    style: {
      flex: 1,
      overflow: "auto",
      padding: "32px 40px",
      display: "flex",
      justifyContent: "center"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      transform: `scale(${zoom})`,
      transformOrigin: "top center",
      transition: "transform 0.2s"
    }
  }, orgTree ? /*#__PURE__*/React.createElement(OrgTreeNode, {
    node: orgTree,
    depth: 0
  }) : /*#__PURE__*/React.createElement("span", {
    style: {
      color: T.muted
    }
  }, "Aucune donn\xE9e d'organigramme.")))));
}
function OrgNodeBox({
  initials,
  name,
  role,
  color,
  size = "md"
}) {
  const big = size === "lg";
  return /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      flexDirection: "column",
      alignItems: "center",
      gap: 6
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      width: big ? 56 : 44,
      height: big ? 56 : 44,
      borderRadius: "50%",
      background: color,
      color: T.white,
      fontSize: big ? 15 : 12,
      fontWeight: 800,
      display: "flex",
      alignItems: "center",
      justifyContent: "center",
      boxShadow: `0 4px 14px ${color}55`,
      flexShrink: 0
    }
  }, initials), /*#__PURE__*/React.createElement("div", {
    style: {
      textAlign: "center"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: big ? 13 : 12,
      fontWeight: 700,
      color: T.dark,
      whiteSpace: "nowrap"
    }
  }, name), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 10.5,
      color: T.muted,
      whiteSpace: "nowrap"
    }
  }, role)));
}
const VLine = ({
  color = "#D0D8F0",
  h = 24
}) => /*#__PURE__*/React.createElement("div", {
  style: {
    width: 2,
    height: h,
    background: color,
    margin: "0 auto"
  }
});
function SEquipe() {
  const [orgOpen, setOrgOpen] = useState(false);
  const orgTree = D.org_tree;
  if (!orgTree) return /*#__PURE__*/React.createElement(Sec, {
    id: "equipe",
    label: "03 \xC9quipe"
  }, /*#__PURE__*/React.createElement(Card, null, /*#__PURE__*/React.createElement("p", {
    style: {
      color: T.muted,
      padding: "20px 0"
    }
  }, "Aucune donn\xE9e d'organigramme. Configurez-le dans l'administration.")));
  const children = orgTree.children || [];
  // Adjoint = premier enfant de même couleur que la direction
  const adjNode = children.find(c => c.couleur === orgTree.couleur) || null;
  // Pôles = enfants directs hors adjoint ; si aucun, on descend dans les enfants de l'adjoint
  let poleNodes = children.filter(c => c !== adjNode);
  if (poleNodes.length === 0 && adjNode) poleNodes = adjNode.children || [];
  poleNodes = poleNodes.slice(0, 4);
  return /*#__PURE__*/React.createElement(Sec, {
    id: "equipe",
    label: "03 \xC9quipe"
  }, /*#__PURE__*/React.createElement(Card, null, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      gap: 10,
      marginBottom: 24
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: 22
    }
  }, "\uD83D\uDC65"), /*#__PURE__*/React.createElement("h2", {
    style: {
      fontSize: 20,
      fontWeight: 800,
      color: T.dark
    }
  }, "Organigramme & \xE9quipe")), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      flexDirection: "column",
      alignItems: "center"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      background: orgTree.couleur + "12",
      border: `2px solid ${orgTree.couleur}33`,
      borderRadius: 16,
      padding: "14px 28px",
      display: "flex",
      gap: 32,
      alignItems: "center"
    }
  }, /*#__PURE__*/React.createElement(OrgNodeBox, {
    initials: orgTree.i,
    name: orgTree.n,
    role: orgTree.r,
    color: orgTree.couleur,
    size: "lg"
  }), adjNode && /*#__PURE__*/React.createElement(React.Fragment, null, /*#__PURE__*/React.createElement("div", {
    style: {
      width: 1,
      height: 48,
      background: orgTree.couleur + "30"
    }
  }), /*#__PURE__*/React.createElement(OrgNodeBox, {
    initials: adjNode.i,
    name: adjNode.n,
    role: adjNode.r,
    color: adjNode.couleur
  }))), poleNodes.length > 0 && /*#__PURE__*/React.createElement(React.Fragment, null, /*#__PURE__*/React.createElement(VLine, {
    h: 28,
    color: orgTree.couleur + "44"
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: "90%",
      display: "flex",
      alignItems: "flex-start",
      justifyContent: "center"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      top: 0,
      left: "8%",
      right: "8%",
      height: 2,
      background: "#D0D8F0"
    }
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "grid",
      gridTemplateColumns: `repeat(${poleNodes.length},1fr)`,
      gap: 0,
      width: "100%"
    }
  }, poleNodes.map(p => {
    const members = (p.children || []).slice(0, 2);
    return /*#__PURE__*/React.createElement("div", {
      key: p.i + p.n,
      style: {
        display: "flex",
        flexDirection: "column",
        alignItems: "center"
      }
    }, /*#__PURE__*/React.createElement(VLine, {
      h: 24,
      color: "#D0D8F0"
    }), /*#__PURE__*/React.createElement("div", {
      style: {
        background: p.couleur + "14",
        border: `2px solid ${p.couleur}44`,
        borderRadius: 14,
        padding: "12px 16px",
        width: "calc(100% - 24px)",
        display: "flex",
        flexDirection: "column",
        alignItems: "center",
        gap: 6
      }
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        fontSize: 9,
        fontWeight: 800,
        color: p.couleur,
        letterSpacing: "0.08em",
        textTransform: "uppercase"
      }
    }, p.r), /*#__PURE__*/React.createElement(OrgNodeBox, {
      initials: p.i,
      name: p.n,
      role: p.r,
      color: p.couleur
    })), members.length > 0 && /*#__PURE__*/React.createElement(React.Fragment, null, /*#__PURE__*/React.createElement(VLine, {
      h: 20,
      color: p.couleur + "55"
    }), /*#__PURE__*/React.createElement("div", {
      style: {
        position: "relative",
        width: "calc(100% - 24px)"
      }
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        position: "absolute",
        top: 0,
        left: "12%",
        right: "12%",
        height: 2,
        background: p.couleur + "44"
      }
    }), /*#__PURE__*/React.createElement("div", {
      style: {
        display: "grid",
        gridTemplateColumns: `repeat(${members.length},1fr)`,
        gap: 6
      }
    }, members.map(m => /*#__PURE__*/React.createElement("div", {
      key: m.i + m.n,
      style: {
        display: "flex",
        flexDirection: "column",
        alignItems: "center"
      }
    }, /*#__PURE__*/React.createElement(VLine, {
      h: 20,
      color: p.couleur + "44"
    }), /*#__PURE__*/React.createElement("div", {
      style: {
        background: T.white,
        border: `1.5px solid ${p.couleur}33`,
        borderRadius: 12,
        padding: "10px 8px",
        width: "100%",
        display: "flex",
        flexDirection: "column",
        alignItems: "center",
        gap: 6
      }
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        width: 36,
        height: 36,
        borderRadius: "50%",
        background: p.couleur + "22",
        color: p.couleur,
        fontSize: 11,
        fontWeight: 800,
        display: "flex",
        alignItems: "center",
        justifyContent: "center"
      }
    }, m.i), /*#__PURE__*/React.createElement("div", {
      style: {
        textAlign: "center"
      }
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        fontSize: 11,
        fontWeight: 700,
        color: T.dark
      }
    }, m.n), /*#__PURE__*/React.createElement("div", {
      style: {
        fontSize: 10,
        color: T.muted
      }
    }, m.r)))))))));
  }))))), /*#__PURE__*/React.createElement("div", {
    style: {
      marginTop: 20,
      display: "flex",
      alignItems: "center",
      justifyContent: "space-between",
      gap: 12,
      padding: "12px 16px",
      background: "#FFFAED",
      borderRadius: 12,
      flexWrap: "wrap"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: 12.5,
      color: "#7A5C00"
    }
  }, "\uD83D\uDCA1 Organigramme simplifi\xE9 \u2013 cliquez pour voir la hi\xE9rarchie compl\xE8te avec toutes les sous-\xE9quipes."), /*#__PURE__*/React.createElement("button", {
    onClick: () => setOrgOpen(true),
    style: {
      display: "inline-flex",
      alignItems: "center",
      gap: 6,
      background: T.dark,
      color: T.white,
      borderRadius: 20,
      padding: "7px 16px",
      fontSize: 12.5,
      fontWeight: 700,
      whiteSpace: "nowrap",
      border: "none",
      cursor: "pointer",
      fontFamily: "'Plus Jakarta Sans',sans-serif"
    }
  }, /*#__PURE__*/React.createElement(Icon, {
    name: "Maximize2",
    size: 13,
    color: T.white
  }), "Voir l'organigramme complet")), orgOpen && /*#__PURE__*/React.createElement(OrgModal, {
    onClose: () => setOrgOpen(false),
    orgTree: orgTree
  })));
}
function SAnnuaire() {
  const [query, setQuery] = useState("");
  const [filterDomaine, setFilterDomaine] = useState("Tous");
  const [open, setOpen] = useState(false);
  const agents = D.agents;
  const domaines = D.domaines || [];
  const domaineColor = Object.fromEntries(domaines.map(d => [d.nom, d.couleur]));
  const domaineList = ["Tous", ...domaines.map(d => d.nom)];
  const filtered = agents.filter(a => {
    const q = query.toLowerCase();
    const matchQ = !q || (a.nom || "").toLowerCase().includes(q) || (a.prenom || "").toLowerCase().includes(q) || (a.role_label || "").toLowerCase().includes(q) || (a.extension || "").includes(q) || (a.poste2 || "").includes(q) || (a.numero_long || "").replace(/\./g, "").includes(q.replace(/\./g, ""));
    return matchQ && (filterDomaine === "Tous" || a.pole === filterDomaine);
  });
  return /*#__PURE__*/React.createElement(Sec, {
    id: "annuaire",
    label: "04 Annuaire"
  }, /*#__PURE__*/React.createElement(Card, null, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      gap: 10,
      marginBottom: open ? 18 : 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: 22
    }
  }, "\uD83D\uDCD2"), /*#__PURE__*/React.createElement("h2", {
    style: {
      fontSize: 20,
      fontWeight: 800,
      color: T.dark,
      flex: 1
    }
  }, "Annuaire de la DSN"), /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: 12,
      color: T.muted,
      marginRight: 8
    }
  }, agents.length, " agents"), /*#__PURE__*/React.createElement("button", {
    onClick: () => setOpen(!open),
    style: {
      display: "inline-flex",
      alignItems: "center",
      gap: 6,
      background: open ? T.dark : T.pill,
      color: open ? T.white : T.navy,
      border: "none",
      borderRadius: 20,
      padding: "7px 16px",
      fontSize: 12.5,
      fontWeight: 700,
      cursor: "pointer",
      fontFamily: "'Plus Jakarta Sans',sans-serif",
      transition: "all 0.15s"
    }
  }, /*#__PURE__*/React.createElement(Icon, {
    name: open ? "ChevronUp" : "ChevronDown",
    size: 13,
    color: open ? T.white : T.navy
  }), open ? "Replier" : "Déplier l'annuaire")), open && /*#__PURE__*/React.createElement(React.Fragment, null, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      gap: 10,
      marginBottom: 16,
      flexWrap: "wrap",
      alignItems: "center"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      gap: 8,
      background: T.bg,
      borderRadius: 10,
      padding: "8px 14px",
      flex: "1 1 200px",
      minWidth: 180
    }
  }, /*#__PURE__*/React.createElement(Icon, {
    name: "Search",
    size: 14,
    color: T.muted
  }), /*#__PURE__*/React.createElement("input", {
    value: query,
    onChange: e => setQuery(e.target.value),
    placeholder: "Nom, pr\xE9nom, poste, n\xB0 de t\xE9l\xE9phone\u2026",
    style: {
      border: "none",
      background: "transparent",
      outline: "none",
      fontSize: 13,
      color: T.dark,
      width: "100%",
      fontFamily: "'Plus Jakarta Sans',sans-serif"
    }
  }), query && /*#__PURE__*/React.createElement("button", {
    onClick: () => setQuery(""),
    style: {
      border: "none",
      background: "none",
      cursor: "pointer",
      color: T.muted,
      fontSize: 16,
      lineHeight: 1,
      padding: 0
    }
  }, "\xD7")), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      gap: 6,
      flexWrap: "wrap"
    }
  }, domaineList.map(p => /*#__PURE__*/React.createElement("button", {
    key: p,
    onClick: () => setFilterDomaine(p),
    style: {
      padding: "6px 14px",
      borderRadius: 20,
      border: "none",
      cursor: "pointer",
      fontFamily: "'Plus Jakarta Sans',sans-serif",
      fontSize: 12,
      fontWeight: 600,
      background: filterDomaine === p ? domaineColor[p] || T.dark : T.bg,
      color: filterDomaine === p ? T.white : T.muted,
      transition: "all 0.15s"
    }
  }, p)))), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 12,
      color: T.muted,
      marginBottom: 12
    }
  }, filtered.length, " agent", filtered.length > 1 ? "s" : "", " affich\xE9", filtered.length > 1 ? "s" : ""), filtered.length === 0 ? /*#__PURE__*/React.createElement("div", {
    style: {
      padding: "32px",
      textAlign: "center",
      color: T.muted,
      fontSize: 14
    }
  }, "Aucun r\xE9sultat.") : /*#__PURE__*/React.createElement("div", {
    style: {
      display: "grid",
      gridTemplateColumns: "repeat(2,1fr)",
      gap: 8
    }
  }, filtered.map(a => {
    const dc = domaineColor[a.pole] || a.couleur || T.navy;
    return /*#__PURE__*/React.createElement("div", {
      key: a.email || a.id,
      style: {
        display: "flex",
        alignItems: "flex-start",
        gap: 10,
        padding: "10px 12px",
        background: dc + "0A",
        borderRadius: 12,
        border: `1.5px solid ${dc}22`
      }
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        width: 36,
        height: 36,
        borderRadius: "50%",
        background: dc,
        color: T.white,
        fontSize: 11,
        fontWeight: 800,
        display: "flex",
        alignItems: "center",
        justifyContent: "center",
        flexShrink: 0,
        marginTop: 2
      }
    }, a.initiales), /*#__PURE__*/React.createElement("div", {
      style: {
        flex: 1,
        minWidth: 0
      }
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        fontWeight: 800,
        fontSize: 12.5,
        color: T.dark,
        lineHeight: 1.2
      }
    }, a.prenom, " ", a.nom), a.role_label && /*#__PURE__*/React.createElement("div", {
      style: {
        fontSize: 11,
        color: T.muted,
        marginBottom: 4
      }
    }, a.role_label), /*#__PURE__*/React.createElement("div", {
      style: {
        display: "flex",
        gap: 8,
        flexWrap: "wrap",
        marginTop: 4
      }
    }, a.extension && /*#__PURE__*/React.createElement("span", {
      style: {
        fontSize: 11,
        color: T.dark,
        fontWeight: 600
      }
    }, "\u260E ", a.extension), a.poste2 && /*#__PURE__*/React.createElement("span", {
      style: {
        fontSize: 11,
        color: T.muted
      }
    }, "\uD83D\uDCDF ", a.poste2), a.numero_long && /*#__PURE__*/React.createElement("span", {
      style: {
        fontSize: 11,
        color: T.muted
      }
    }, "\uD83D\uDCF1 ", a.numero_long)), /*#__PURE__*/React.createElement("a", {
      href: `mailto:${a.email}`,
      style: {
        fontSize: 11,
        color: dc,
        display: "block",
        marginTop: 3,
        overflow: "hidden",
        textOverflow: "ellipsis",
        whiteSpace: "nowrap"
      }
    }, a.email)));
  })))));
}
function SOutils() {
  return /*#__PURE__*/React.createElement(Sec, {
    id: "outils",
    label: "05 Outils"
  }, /*#__PURE__*/React.createElement(Card, null, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      gap: 10,
      marginBottom: 18
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: 22
    }
  }, "\uD83D\uDEE0\uFE0F"), /*#__PURE__*/React.createElement("h2", {
    style: {
      fontSize: 20,
      fontWeight: 800,
      color: T.dark
    }
  }, "Votre environnement de travail")), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "grid",
      gridTemplateColumns: "repeat(3,1fr)",
      gap: 10,
      marginBottom: 14
    }
  }, D.outils.map(o => /*#__PURE__*/React.createElement("div", {
    key: o.id,
    style: {
      padding: "14px 16px",
      background: o.couleur + "12",
      borderRadius: 14,
      border: `1.5px solid ${o.couleur}22`
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      gap: 8,
      marginBottom: 10
    }
  }, /*#__PURE__*/React.createElement(IconBox, {
    name: o.icone,
    color: o.couleur,
    size: 28,
    iconSize: 14
  }), /*#__PURE__*/React.createElement("span", {
    style: {
      fontWeight: 700,
      fontSize: 12.5,
      color: T.dark
    }
  }, o.categorie)), (o.items || []).map(it => /*#__PURE__*/React.createElement("div", {
    key: it,
    style: {
      display: "flex",
      alignItems: "center",
      gap: 7,
      marginBottom: 5,
      fontSize: 12.5,
      color: T.muted
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      width: 4,
      height: 4,
      borderRadius: "50%",
      background: o.couleur,
      flexShrink: 0
    }
  }), it))))), /*#__PURE__*/React.createElement("div", {
    style: {
      padding: "11px 16px",
      background: T.pill,
      borderRadius: 12,
      fontSize: 13,
      color: T.navy
    }
  }, "\uD83D\uDD10 ", /*#__PURE__*/React.createElement("strong", null, "Acc\xE8s aux outils :"), " Comptes cr\xE9\xE9s lors de la 1\xE8re semaine. Ne partagez jamais vos identifiants.")));
}
function SProcedures() {
  const [open, setOpen] = useState(null);
  return /*#__PURE__*/React.createElement(Sec, {
    id: "procedures",
    label: "06 Proc\xE9dures"
  }, /*#__PURE__*/React.createElement(Card, null, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      gap: 10,
      marginBottom: 18
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: 22
    }
  }, "\uD83D\uDCCB"), /*#__PURE__*/React.createElement("h2", {
    style: {
      fontSize: 20,
      fontWeight: 800,
      color: T.dark
    }
  }, "Proc\xE9dures & guides")), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      flexDirection: "column",
      gap: 8
    }
  }, D.procedures.map(p => /*#__PURE__*/React.createElement("div", {
    key: p.id,
    style: {
      borderRadius: 14,
      overflow: "hidden",
      background: open === p.id ? p.couleur + "0E" : "#F7F9FF",
      border: `1.5px solid ${open === p.id ? p.couleur + "44" : "#E8EDFF"}`,
      transition: "all 0.15s"
    }
  }, /*#__PURE__*/React.createElement("button", {
    onClick: () => setOpen(open === p.id ? null : p.id),
    style: {
      display: "flex",
      alignItems: "center",
      gap: 12,
      width: "100%",
      padding: "12px 16px",
      background: "none",
      border: "none",
      cursor: "pointer",
      fontFamily: "'Plus Jakarta Sans',sans-serif",
      textAlign: "left"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      width: 32,
      height: 32,
      borderRadius: 10,
      background: p.couleur,
      display: "flex",
      alignItems: "center",
      justifyContent: "center",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      width: 12,
      height: 12,
      borderRadius: 2,
      background: "rgba(255,255,255,0.7)"
    }
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      flex: 1
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: 13.5,
      fontWeight: 600,
      color: T.dark
    }
  }, p.titre)), /*#__PURE__*/React.createElement(PillTag, {
    label: p.tag,
    color: p.couleur
  }), /*#__PURE__*/React.createElement("span", {
    style: {
      color: T.muted,
      fontSize: 18,
      transform: open === p.id ? "rotate(90deg)" : "none",
      transition: "transform 0.2s"
    }
  }, "\u203A")), open === p.id && /*#__PURE__*/React.createElement("div", {
    style: {
      padding: "0 16px 14px 60px"
    }
  }, /*#__PURE__*/React.createElement("ol", {
    style: {
      paddingLeft: 16,
      display: "flex",
      flexDirection: "column",
      gap: 5
    }
  }, (p.steps || []).map((s, i) => /*#__PURE__*/React.createElement("li", {
    key: i,
    style: {
      fontSize: 13,
      color: T.muted,
      lineHeight: 1.55
    }
  }, s)))))))));
}
function SContacts() {
  return /*#__PURE__*/React.createElement(Sec, {
    id: "contacts",
    label: "07 Contacts"
  }, /*#__PURE__*/React.createElement(Card, null, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      gap: 10,
      marginBottom: 18
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: 22
    }
  }, "\uD83D\uDCDE"), /*#__PURE__*/React.createElement("h2", {
    style: {
      fontSize: 20,
      fontWeight: 800,
      color: T.dark
    }
  }, "Contacts utiles")), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "grid",
      gridTemplateColumns: "repeat(2,1fr)",
      gap: 10
    }
  }, D.contacts.map(c => /*#__PURE__*/React.createElement("div", {
    key: c.id,
    style: {
      display: "flex",
      alignItems: "center",
      gap: 12,
      padding: "12px 16px",
      background: c.couleur + "0E",
      borderRadius: 14,
      border: `1.5px solid ${c.couleur}22`
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      width: 40,
      height: 40,
      borderRadius: "50%",
      background: c.couleur,
      display: "flex",
      alignItems: "center",
      justifyContent: "center",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: 14,
      color: T.white,
      fontWeight: 800
    }
  }, (c.nom || "")[0])), /*#__PURE__*/React.createElement("div", {
    style: {
      flex: 1,
      minWidth: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontWeight: 700,
      fontSize: 13,
      color: T.dark
    }
  }, c.nom), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 11.5,
      color: T.muted,
      marginBottom: 4
    }
  }, c.role_label), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      gap: 10,
      flexWrap: "wrap"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: 11.5,
      fontWeight: 600,
      color: T.dark
    }
  }, "ext. ", c.extension), /*#__PURE__*/React.createElement("a", {
    href: `mailto:${c.email}`,
    style: {
      fontSize: 11.5,
      color: c.couleur
    }
  }, c.email))))))));
}
function SPlanning() {
  return /*#__PURE__*/React.createElement(Sec, {
    id: "planning",
    label: "08 Planning"
  }, /*#__PURE__*/React.createElement(Card, null, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      gap: 10,
      marginBottom: 18
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: 22
    }
  }, "\uD83D\uDCC5"), /*#__PURE__*/React.createElement("h2", {
    style: {
      fontSize: 20,
      fontWeight: 800,
      color: T.dark
    }
  }, "Horaires & organisation")), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "grid",
      gridTemplateColumns: "1fr 1fr",
      gap: 14
    }
  }, /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 11,
      fontWeight: 700,
      color: T.muted,
      textTransform: "uppercase",
      letterSpacing: "0.07em",
      marginBottom: 10
    }
  }, "Heures d'ouverture"), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      flexDirection: "column",
      gap: 8
    }
  }, D.horaires.map(h => /*#__PURE__*/React.createElement("div", {
    key: h.id,
    style: {
      display: "flex",
      alignItems: "center",
      gap: 12,
      padding: "12px 16px",
      background: h.couleur + "12",
      borderRadius: 12
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      width: 8,
      height: 8,
      borderRadius: "50%",
      background: h.couleur,
      flexShrink: 0
    }
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      flex: 1
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 13,
      fontWeight: 600,
      color: T.dark
    }
  }, h.jour), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 11.5,
      color: T.muted
    }
  }, h.type_horaire)), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 12.5,
      fontWeight: 700,
      color: h.couleur
    }
  }, h.horaire))))), /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 11,
      fontWeight: 700,
      color: T.muted,
      textTransform: "uppercase",
      letterSpacing: "0.07em",
      marginBottom: 10
    }
  }, "Astreintes 24h/24"), /*#__PURE__*/React.createElement("div", {
    style: {
      background: T.pill,
      borderRadius: 14,
      padding: "16px",
      display: "flex",
      flexDirection: "column",
      gap: 9,
      marginBottom: 10
    }
  }, ["Disponible 24h/24 – 7j/7 au poste d'astreinte (ext. 5001).", "Interventions pour pannes impactant les soins uniquement.", "Planning mensuel diffusé à l'équipe en fin de mois."].map((a, i) => /*#__PURE__*/React.createElement("div", {
    key: i,
    style: {
      display: "flex",
      gap: 9
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      width: 5,
      height: 5,
      borderRadius: "50%",
      background: T.navy,
      marginTop: 7,
      flexShrink: 0
    }
  }), /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: 12.5,
      color: T.navy,
      lineHeight: 1.5
    }
  }, a)))), /*#__PURE__*/React.createElement("div", {
    style: {
      padding: "11px 14px",
      background: "#FFF9EC",
      borderRadius: 12,
      fontSize: 12.5,
      color: "#7A4A00"
    }
  }, "\u23F0 ", /*#__PURE__*/React.createElement("strong", null, "1\xE8re semaine :"), " Convenez de vos horaires et jours de t\xE9l\xE9travail avec votre responsable.")))));
}
function SPratique() {
  return /*#__PURE__*/React.createElement(Sec, {
    id: "pratique",
    label: "09 Infos pratiques"
  }, /*#__PURE__*/React.createElement(Card, null, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      gap: 10,
      marginBottom: 18
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: 22
    }
  }, "\u2139\uFE0F"), /*#__PURE__*/React.createElement("h2", {
    style: {
      fontSize: 20,
      fontWeight: 800,
      color: T.dark
    }
  }, "Tout ce qu'il faut savoir")), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "grid",
      gridTemplateColumns: "repeat(3,1fr)",
      gap: 10
    }
  }, D.pratiques.map(info => /*#__PURE__*/React.createElement("div", {
    key: info.id,
    style: {
      padding: "14px 16px",
      background: info.couleur + "0E",
      borderRadius: 14,
      border: `1.5px solid ${info.couleur}18`
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      marginBottom: 10
    }
  }, /*#__PURE__*/React.createElement(IconBox, {
    name: info.icone,
    color: info.couleur,
    size: 38,
    iconSize: 18
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      fontWeight: 700,
      fontSize: 13.5,
      color: T.dark,
      marginBottom: 4
    }
  }, info.titre), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 12.5,
      color: T.muted,
      lineHeight: 1.5
    }
  }, info.contenu))))));
}
function SRessources() {
  return /*#__PURE__*/React.createElement(Sec, {
    id: "ressources",
    label: "10 Ressources"
  }, /*#__PURE__*/React.createElement(Card, null, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      gap: 10,
      marginBottom: 18
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: 22
    }
  }, "\uD83D\uDD17"), /*#__PURE__*/React.createElement("h2", {
    style: {
      fontSize: 20,
      fontWeight: 800,
      color: T.dark
    }
  }, "Liens & ressources utiles")), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "grid",
      gridTemplateColumns: "repeat(3,1fr)",
      gap: 12
    }
  }, D.ressources.map(r => /*#__PURE__*/React.createElement("div", {
    key: r.id,
    style: {
      borderRadius: 16,
      overflow: "hidden",
      border: `1.5px solid ${r.couleur}22`
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      background: r.couleur,
      padding: "10px 16px",
      display: "flex",
      alignItems: "center",
      gap: 8
    }
  }, /*#__PURE__*/React.createElement(Icon, {
    name: r.icone,
    size: 14,
    color: "rgba(255,255,255,0.85)"
  }), /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: 12,
      fontWeight: 800,
      color: T.white,
      letterSpacing: "0.05em",
      textTransform: "uppercase"
    }
  }, r.categorie)), /*#__PURE__*/React.createElement("div", {
    style: {
      background: r.couleur + "08"
    }
  }, (r.links || []).map((l, i) => /*#__PURE__*/React.createElement("a", {
    key: l.id || i,
    href: l.url || "#",
    style: {
      display: "flex",
      alignItems: "center",
      gap: 10,
      padding: "10px 14px",
      borderBottom: i < r.links.length - 1 ? `1px solid ${r.couleur}18` : "none"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      width: 28,
      height: 28,
      borderRadius: 8,
      background: r.couleur + "22",
      display: "flex",
      alignItems: "center",
      justifyContent: "center",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(Icon, {
    name: r.icone,
    size: 13,
    color: r.couleur
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      flex: 1
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 13,
      fontWeight: 600,
      color: T.dark
    }
  }, l.label), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 11,
      color: T.muted
    }
  }, l.description)), /*#__PURE__*/React.createElement("span", {
    style: {
      color: r.couleur,
      fontSize: 16
    }
  }, "\u203A")))))))));
}
function SFAQ() {
  const [open, setOpen] = useState(0);
  const cfg = D.config || {};
  const faqTitre = cfg.helpdesk_faq_titre || "Vous n'avez pas trouvé votre réponse ?";
  const helpDispo = cfg.helpdesk_disponibilite || 'lun–ven, 7h30–18h30';
  const helpCta = cfg.helpdesk_cta || 'Contacter le helpdesk';
  const helpEmail = cfg.helpdesk_email || 'helpdesk@chu-angers.fr';
  return /*#__PURE__*/React.createElement(Sec, {
    id: "faq",
    label: "11 FAQ"
  }, /*#__PURE__*/React.createElement(Card, null, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      gap: 10,
      marginBottom: 18
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: 22
    }
  }, "\u2753"), /*#__PURE__*/React.createElement("h2", {
    style: {
      fontSize: 20,
      fontWeight: 800,
      color: T.dark
    }
  }, "Questions fr\xE9quentes")), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      flexDirection: "column",
      gap: 8,
      marginBottom: 18
    }
  }, D.faq.map((f, i) => /*#__PURE__*/React.createElement("div", {
    key: f.id,
    style: {
      borderRadius: 14,
      overflow: "hidden",
      background: open === i ? T.pill : "#F7F9FF",
      border: `1.5px solid ${open === i ? T.cyan + "66" : "#E8EDFF"}`,
      transition: "all 0.15s"
    }
  }, /*#__PURE__*/React.createElement("button", {
    onClick: () => setOpen(open === i ? null : i),
    style: {
      display: "flex",
      alignItems: "center",
      gap: 12,
      width: "100%",
      padding: "13px 16px",
      background: "none",
      border: "none",
      cursor: "pointer",
      fontFamily: "'Plus Jakarta Sans',sans-serif",
      textAlign: "left"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      flex: 1,
      fontSize: 13.5,
      fontWeight: 600,
      color: T.dark
    }
  }, f.question), /*#__PURE__*/React.createElement("span", {
    style: {
      color: T.cyan,
      fontSize: 20,
      fontWeight: 300,
      transform: open === i ? "rotate(45deg)" : "none",
      transition: "transform 0.2s"
    }
  }, "+")), open === i && /*#__PURE__*/React.createElement("div", {
    style: {
      padding: "0 16px 14px",
      fontSize: 13,
      color: T.muted,
      lineHeight: 1.65
    }
  }, f.reponse)))), /*#__PURE__*/React.createElement("div", {
    style: {
      background: T.dark,
      borderRadius: 18,
      padding: "20px 24px",
      display: "flex",
      alignItems: "center",
      justifyContent: "space-between",
      flexWrap: "wrap",
      gap: 14
    }
  }, /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 16,
      fontWeight: 800,
      color: T.white,
      marginBottom: 3
    }
  }, faqTitre), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 12.5,
      color: "rgba(255,255,255,0.45)"
    }
  }, "Helpdesk disponible ", helpDispo)), /*#__PURE__*/React.createElement(DarkBtn, {
    href: `mailto:${helpEmail}`,
    style: {
      background: T.cyan
    }
  }, helpCta))));
}

/* ── App ── */
function App() {
  const [active, setActive] = useState("bienvenue");
  useEffect(() => {
    const obs = new IntersectionObserver(entries => {
      entries.forEach(e => {
        if (e.isIntersecting) setActive(e.target.id);
      });
    }, {
      rootMargin: "-30% 0px -60% 0px"
    });
    NAV.forEach(n => {
      const el = document.getElementById(n.id);
      if (el) obs.observe(el);
    });
    return () => obs.disconnect();
  }, []);
  return /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      width: "100%",
      minHeight: "100vh"
    }
  }, /*#__PURE__*/React.createElement(Sidebar, {
    active: active
  }), /*#__PURE__*/React.createElement("main", {
    style: {
      flex: 1,
      minWidth: 0,
      padding: "28px 28px 28px 24px",
      overflowY: "auto"
    }
  }, /*#__PURE__*/React.createElement(SBienvenue, null), /*#__PURE__*/React.createElement(SService, null), /*#__PURE__*/React.createElement(SEquipe, null), /*#__PURE__*/React.createElement(SAnnuaire, null), /*#__PURE__*/React.createElement(SOutils, null), /*#__PURE__*/React.createElement(SProcedures, null), /*#__PURE__*/React.createElement(SContacts, null), /*#__PURE__*/React.createElement(SPlanning, null), /*#__PURE__*/React.createElement(SPratique, null), /*#__PURE__*/React.createElement(SRessources, null), /*#__PURE__*/React.createElement(SFAQ, null), /*#__PURE__*/React.createElement("div", {
    style: {
      padding: "16px 0",
      display: "flex",
      alignItems: "center",
      justifyContent: "space-between"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      gap: 12
    }
  }, /*#__PURE__*/React.createElement("img", {
    src: "logo-chu.png",
    alt: "CHU Angers",
    style: {
      height: 24
    }
  }), /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: 12,
      color: T.muted
    }
  }, "Direction du Syst\xE8me Num\xE9rique \xB7 CHU d'Angers")), /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: 12,
      color: "#B0BAD8"
    }
  }, "\xA9 ", new Date().getFullYear()))));
}
ReactDOM.createRoot(document.getElementById("root")).render(/*#__PURE__*/React.createElement(App, null));
